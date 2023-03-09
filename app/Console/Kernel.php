<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Torann\Currency\Console\Manage;
use Torann\Currency\Console\Update;
use Torann\Currency\Console\Cleanup;
use Modules\Audio\Entities\Audio;
use Modules\Album\Entities\Album;
use Modules\Artist\Entities\Artist;
use Illuminate\Support\Facades\Artisan;
use App\TopDetail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Update::class,
        Cleanup::class,
        Manage::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        $schedule->call(function () {
            
            Artisan::call('currency:update -o');
            $getTopAlbum = Album::where('listening_count','>','0')->orderBy('listening_count','desc')->limit(15)->get();
            $checkData = TopDetail::all();    
            $add = 0;
            if(sizeof($getTopAlbum) > 0){
                $albumArr = [];
                foreach($getTopAlbum as $album){
                    array_push($albumArr, $album->id);
                }
                if(!empty($albumArr) && sizeof($checkData) > 0){
                    $update = TopDetail::where('id',$checkData[0]->id)->update(['top_album'=>json_encode($albumArr)]);
                }else{
                    $add = TopDetail::create(['top_album'=>json_encode($albumArr)]);
                }
            }
            $id = ($add) ? $add->id : $checkData[0]->id;
                 
            $getTopArtist = Artist::where('listening_count','>','0')->orderBy('listening_count','desc')->limit(10)->get();
            
            if(!empty($getTopArtist)){
                $artistArr = [];
                foreach($getTopArtist as $artist){
                    array_push($artistArr, $artist->id);
                }
                if(!empty($artistArr)){
                    $update = TopDetail::where('id',$id)->update(['top_artist'=>json_encode($artistArr)]);
                }
            }
    
            $getTopAudio = Audio::where('listening_count','>','0')->orderBy('listening_count','desc')->limit(15)->get();
            if(!empty($getTopAudio)){
                $audioArr = [];
                foreach($getTopAudio as $audio){
                    array_push($audioArr, $audio->id);
                }
                if(!empty($audioArr)){
                    $update = TopDetail::where('id',$id)->update(['top_audio'=>json_encode($audioArr)]);
                }
            }
            
        });  
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
