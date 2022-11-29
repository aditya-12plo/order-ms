<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class EmailNotification extends Mailable {
 
    use Queueable, SerializesModels;
		
	public $title 		= '';
	public $content 	= '';
	public $attachments = array();
	
	public function __construct($title, $content , $attachments=array()) {
		$this->title        = $title;
		$this->content      = $content;
		$this->attachments  = $attachments;
	}
	
    //build the message.
    public function build() {

        $APP_ENV 	= env('APP_ENV');
        $titles 	= $this->title;

        if($APP_ENV == "development") $titles =  env('APP_ENV')." ".$this->title;

        $mail = $this->subject($titles)->view('emails.index')->with(['content' => $this->content]);
		
		if(count($this->attachments) > 0){
			foreach ( $this->attachments as $attachment ) {
				$mail->attach($attachment['file'],$attachment['options']);
			}
		}
		
		return $mail;
    }
}