<?php namespace Techmaster\Notifications;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

abstract class BaseEmailNotification
{

	/**
	 * Subject
	 * @var string
	 */
	protected $subject = 'Sem assunto';

	/**
	 * Blade template
	 * @var string
	 */
	protected $template = '';

	/**
	 * Who deliver the message
	 * @var string
	 */
	public $fromEmail = '';
	public $fromName = '';

	/**
	 * Who will receive the message
	 * @var string
	 */
	public $toEmail = '';
	public $toName = '';

	/**
	 * Who reply to
	 * @var string
	 */
	public $replyTo = '';


	/**
	 * List of emails
	 * @var array
	 */
	protected $cc = [];

	/**
	 * Array to be passed into template
	 * @var array
	 */
	protected $data = [];

	public function send()
	{
		$self = $this;

		$this->validateEmailData();

		Mail::send($this->template, $this->getData(), function ($message) use ($self)
		{
			$message->from($self->fromEmail, $self->fromName);

			$message->to($self->toEmail, $self->toName);

			if($reply = $this->getReplyTo()){
				$message->replyTo($reply);
			}

			$message->subject($self->getSubject());

			if(count($ccs = $self->getCc()) > 0)
			{
				foreach($ccs as $email)
				{
					$message->cc($email);
				}
			}
		});
	}

	private function validateEmailData()
	{
		if (strlen($this->template) == 0)
		{
			throw new Exception("O template não foi definido");
		}
	}

	private function exportHtml()
	{
		if(Config::get('mail.pretend'))
		{

		}
	}

	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param string $subject
	 * @return $this
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getData()
	{
		if(isset($this->data['message']))
		{
			throw new Exception("Não pode haver dado com indice [message].");
		}
		return $this->data;
	}

	/**
	 * @param array $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getCc()
	{
		return $this->cc;
	}

	/**
	 * @param string|array $cc
	 */
	public function cc($cc)
	{
		$this->cc = array_merge($this->cc, (array)$cc);
	}

	/**
	 * @return string
	 */
	public function getReplyTo()
	{
		return strlen($this->replyTo) === 0 ? false : $this->replyTo;
	}

	/**
	 * @param string $replyTo
	 */
	public function setReplyTo($replyTo)
	{
		$this->replyTo = $replyTo;
	}


}