<?php


namespace LaravelClass\LaraAuth\Service\Implementation\CoreTraits;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function LaravelClass\LaraAuth\helpers\makeMessage;

trait VerifyLink
{
    private function verifyEmailLinkCore($guard = 'web')
    {
        if ($this->checkIsLinkSignTrue() && $this->canUserVerifyEmail($guard) && $this->setVerifyEmail($guard))
        {

            return strlen(config('laraAuth.email.verifyEmail.redirectAfterVerified.'.$guard)) > 1 ? ['response' =>

                redirect()->route(config('laraAuth.email.verifyEmail.redirectAfterVerified.'.$guard)),'state' => true] : ['response' => '','result' => true];
        }

        return [
            'response' => '',

            'state' => false
        ];
    }

    private function verifyPasswordResetLinkCore($guard = 'web' , $dbName = 'User')
    {
        $rules = config('laraAuth.auth.validation.resetPassword.rules.'.$guard);

        $existField = config('laraAuth.auth.validation.resetPassword.existsField.'.$guard);

        $rules[$existField][] = 'exists:\App\Models\\'.ucfirst($dbName).','.'email';

        $messages = config('laraAuth.auth.validation.resetPassword.messages.'.$guard);

        $customAttributes = config('laraAuth.auth.validation.resetPassword.customAttributes.'.$guard);

        $validated = Validator::make(request()->all(),$rules , $messages , $customAttributes);

        if ($validated->fails())
        {
            return  [
                'response' => back()->withErrors($validated->errors()),
                'errorMessages' =>  $validated->errors()->getMessages(),
                'state' => false
            ];
        }

        if ($this->checkResetPasswordToken($guard , $dbName))
        {
            $email = $validated->safe()->all()[config('laraAuth.email.resetPassword.actionLinkParams.'.$guard)['email']];

            $dbName = '\App\Models\\'.$dbName;

            $user = $dbName::query()->where('email',$email)->first();

            $user->update(['password'=>Hash::make($validated->safe()->all()['password'])]);

            DB::table('password_resets')->where('email',$user->email)->delete();

            Auth::guard($guard)->logoutOtherDevices($user->password);

            $msgKey = array_key_first(config('laraAuth.auth.validation.resetPassword.successfulMessage.'.$guard));

            $successFulMessage = makeMessage(config('laraAuth.auth.validation.resetPassword.successfulMessage.'.$guard));

            return [

                'response' => redirect()->route(config('laraAuth.auth.validation.resetPassword.redirectAfterPasswordReset.'.$guard))

                    ->with($msgKey , config('laraAuth.auth.validation.resetPassword.successfulMessage.'.$guard)[$msgKey]),

                'successfulMessage' => $successFulMessage,

                'state' => true

            ];
        }
        $errorMessages = makeMessage(config('laraAuth.auth.validation.resetPassword.errorMessages.'.$guard));

        return [
            'response' => back()->withErrors($errorMessages),
            'errorMessages' => $errorMessages->getMessages(),
            'state' => false
        ];
    }

    private function checkIsLinkSignTrue()
    {
        return request()->hasValidSignature();
    }

    private function canUserVerifyEmail($guard)
    {
        return ! Auth::guard($guard)->user()->email_verified_at;
    }

    public function canUserResetPasswordCore($guard = 'web')
    {
        return $this->checkIsLinkSignTrue() && $this->checkUserResetPasswordLinkCredentials($guard);
    }


    private function checkUserResetPasswordLinkCredentials($guard){

        $params = array_values(config('laraAuth.email.resetPassword.actionLinkParams'));

        return request()->has($params[0]) && request()->has($params[1]) && $this->checkResetPasswordToken($guard);

    }

    private function checkResetPasswordToken($guard)
    {
        $tokenInfo = DB::table('password_resets')->where('email',request()
            ->get(config('laraAuth.email.resetPassword.actionLinkParams.'.$guard)['email']))->first();

        $dbToken = $tokenInfo ? $tokenInfo->token : '';

        return  User::query()->where('email',request()->
    get(config('laraAuth.email.resetPassword.actionLinkParams.'.$guard)['email']))->exists()

            && request()->get(config('laraAuth.email.resetPassword.actionLinkParams.'.$guard)['token']) == $dbToken;
    }

    private function setVerifyEmail($guard){

        if (Auth::guard($guard)->user()->id == request()->get(config('laraAuth.email.verifyEmail.actionLinkParams.'.$guard)['id']) && sha1(Auth::guard($guard)->user()->email) == request()->get(config('laraAuth.email.verifyEmail.actionLinkParams.'.$guard)['hash'])){
            $user =  Auth::guard($guard)->user();

            $user->email_verified_at = Carbon::now();

            $user->save();

            return true;
        }

        return false;
    }
}
