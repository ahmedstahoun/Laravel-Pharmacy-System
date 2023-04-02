<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        
        // if ($request->user()->hasVerifiedEmail()) {
        //     return [
        //         'message' => 'Already Verified'
        //     ];
        // }
        $userID = $request->id;
        $user = User::find($userID);
        $user->sendEmailVerificationNotification();

		return response()->json([
			'Email Sent' => 'Verification Email is sent to you email, Check out and follow the verification link',
        	'Verfication Email' => 'If you didn\'t receive any verification Email click '.route('verification.resend', $user->id),
        	'Data' => $user,
            ], 403);
    }

    public function verify(Request $request)
	{
		$userID = $request['id'];
		$user = User::findOrFail($userID);
		$date = date("Y-m-d g:i:s");
		$user->email_verified_at = $date; 
		$user->save();
		return response()->json('Congrats! Email verified');
	}

    public function resend(Request $request)
	{

		$user = User::find($request->id);
		if ($user->email_verified_at) {
		return response()->json('User already verified email', 422);
	}
		$user->sendEmailVerificationNotification();
		return response()->json('The notification has been resubmitted');
	}

}
