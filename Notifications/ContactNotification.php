<?php namespace Techmaster\Notifications;

class ContactNotification extends BaseEmailNotification
{

	/**
	 * Subject
	 * @var string
	 */
	protected $subject = 'Contato feito por ';


	/**
	 * Blade template
	 * @var string
	 */
	protected $template = 'emails.contact';


	/**
	 * Who deliver the message
	 * @var string
	 */
	public $fromEmail = 'notify@company.com.br';
	public $fromName = 'company';

	/**
	 * Who will receive the message
	 * @var string
	 */
	public $toEmail = 'notify@company.com.br';
	public $toName = 'company';


	function __construct(array $input)
	{
		$this->setData([
			'name'  => isset($input['name']) ? $input['name'] : '',
			'email' => isset($input['email']) ? $input['email'] : '',
			'phone' => isset($input['phone']) ? $input['phone'] : '',
			'body'  => isset($input['message']) ? $input['message'] : '',
		]);

		$this->setSubject($this->subject . ' ' . $input['name']);
//		$this->cc(['web@conceito.com']);
		$this->setReplyTo($input['email']);
	}

}