<?php
return [
	'/' => 'site/index',
	'about-us' => 'page/default/aboutus',
	'how' => 'page/default/howquizzyworks',
	'faq' => 'page/default/faqs',
	'contact-us' => 'support/default/index',
	'privacy' => 'page/default/privacy',
	'recover' => 'user/recover/index',

	'news' => 'news/default/index',
	'news/<title>' => 'news/default/view',

	'login' => 'user/login/index',
	'register' => 'user/register/index',
	'logout' => 'user/logout/index',
	'dashboard' => 'user/default/index',
	'settings/<tab>' => 'user/default/settings',
	'settings' => 'user/default/settings',
	'search' => 'page/search/index',

	'<username>' => 'user/profile/index',
	'<username>/folders' => 'folder/view/user',
	'<username>/folder/<keyword>' => 'folder/view/info',
	'<username>/change/profile-picture/<pictureKey>' => 'user/profile/changepicture',
	'upload/profile-picture' => 'user/profile/uploadpicture',

	'<username>/account/activation' => 'user/activate/validateemail',
	'<username>/key/resend' => 'user/activate/resendkey',

	'<username>/set' => 'set/default/user',
	'set/<id>' => 'set/default/view',
	'set/audio/list' => 'set/default/audiolist',
	'set/<id>/flash-cards' => 'set/flash/play',
	'set/<id>/learn' => 'set/learn/play',
	'set/<id>/speller' => 'set/speller/play',
	'set/<id>/test' => 'set/test/play',

	'folder/edit/permission' => 'folder/edit/attempt',
	'folder/edit' => 'folder/edit/index',
	'folder/delete/permission' => 'folder/delete/attempt',
	'folder/delete' => 'folder/delete/index',
	'folder/create' => 'folder/create/index',

	'<username>/class' => 'classes/default/user',
	'class/create' => 'classes/create/index',
	'class/<id>' => '/classes/default/view',
	'class/<id>/members' => 'classes/default/members',
	'class/edit' => 'classes/edit/index',
	'class/edit/permission' => 'classes/edit/permission',
	'class/delete' => 'classes/delete/index',
	'class/request/access' => 'classes/join/index',
	'class/request/cancel' => 'classes/delete/request',
	'class/drop/access' => 'classes/delete/drop',

	'subscription/paypal/cancel/<key>' => 'subscription/paypal/cancel',
	'subscription/paypal/success/<key>' => 'subscription/paypal/success',
	'subscription/paypal/ipn/<key>' => 'subscription/paypal/ipn',
];