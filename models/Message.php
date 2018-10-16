<?php
namespace app\models;

class Message
{
	public function parentMessage($params)
	{
		$subject = 'Quizzy - Parent Account Activation';
		$message = '
			Hello,
			<br /><br />
			You are receiving this email because ' . $params['studentName'] . ' invited you at www.quizzy.sg.
			<br /><br />
			Your user details are as follows:
			E-mail: ' . $params['parentEmail'] . '<br />
			<br />
			Kindly click on the activation URL below to get started!<br />
			Activation URL : ' . $params['activationUrl'] . '<br />
			<br />
			Thank You.<br />
			<br />
			Happy Learning and Playing,<br />
			The Quizzy SG Team
		';

		return array(
			'subject' => $subject,
			'message' => $message,
		);
	}
}