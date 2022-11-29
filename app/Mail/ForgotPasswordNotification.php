<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class ForgotPasswordNotification extends Mailable {
 
    use Queueable, SerializesModels;
		
	public $title       = '';
	public $datas       = '';
	public $link        = '';
	
	public function __construct($title, $datas, $link) {
		$this->title	    = $title;
		$this->datas        = $datas;
		$this->link         = $link;
	}
	
    //build the message.
    public function build() {

        $APP_ENV 	= env('APP_ENV');
        $titles 	= $this->title;

        if($APP_ENV == "development") $titles =  env('APP_ENV')." ".$this->title;

        $mail = $this->subject($titles)->view('emails.forgot-password')->with(['datas' => $this->datas, "link" => $this->link]);
		
		
		return $mail;
    }
}