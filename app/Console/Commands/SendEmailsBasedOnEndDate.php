<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActionItem; 
use Illuminate\Support\Facades\Mail;
use App\Mail\ActionItemReminderMail;
use Carbon\Carbon;

class SendEmailsBasedOnEndDate extends Command
{
    protected $signature = 'send:emails';
    protected $description = 'Send emails based on end date condition';

    public function handle()
    {
        // Check the end date and send email
        $records = ActionItem::whereDate('due_date', today()) // Check for records due today
            ->orWhereDate('due_date', Carbon::today()->addDay()) // Check for records due tomorrow
            ->get();

        foreach ($records as $record) {

            $user = User::findOrFail($record->user_id);
 
            try {
                $details = [
                    'title' => 'Your Action Item',
                    'name' => $user->name,
                    'body' => 'You have an action item called : '.$request->task .' , that ends soon, please review it!',
                    'start_date' => $request->date,
                    'end_date' => $request->due_date,
                ];
                
                Mail::to($user->email)->send(new ActionItemReminderMail($details));
                
            } catch (Exception $e) {
    
                info("Error: ". $e->getMessage());
            }
        }

        $this->info('Emails sent successfully!');
    }
}