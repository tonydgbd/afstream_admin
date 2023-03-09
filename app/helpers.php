<?php

    use Illuminate\Support\Facades\Validator;
    use Modules\Artist\Entities\ArtistGenre;
    use Modules\Audio\Entities\AudioArtist;
    use Illuminate\Support\Facades\Storage;
    use Modules\Setting\Entities\Settings;
    use Modules\Setting\Entities\Currency;
    use Modules\Artist\Entities\Artist;
    use Alaouy\Youtube\Facades\Youtube;
    use wapmorgan\MediaFile\MediaFile;
    use Modules\Album\Entities\Album;
    use Modules\Audio\Entities\Audio;
    use Modules\Radio\Entities\Radio;
    use App\Favourite;

    function assetPath(){
        $path = base_path();
        $arr = explode('/',$path);
        unset($arr[count($arr)-1]);
        return $arr;
    }

    function validation($data, $rules, $msg=''){
        $resp = array('status'=>'1');
        $validator = Validator::make(array_change_key_case($data), array_change_key_case($rules));
        if ($validator->fails()){    
            $error = response()->json($validator->errors()->all(), 200);
            $resp = array('status'=>0, 'msg'=>($msg != '' ? $msg : $error->original[0]));
        }
        return $resp;
    }

    function delete_file_if_exist($path){
        if(file_exists($path)){
            unlink($path);
        }
    }

    function upload_image($image, $path, $imageName, $imgSize=''){
        $optimizeImage = Image::make($image);
        $optimizePath = $path;
        $name = $imageName;
        if($imgSize != ''){
            $imgRatio = explode('x', $imgSize);
            $optimizeImage->resize($imgRatio[0], $imgRatio[1]);
        }
        $optimizeImage->save($optimizePath.$name, 70);     
    }

    function upload_video($param){
        extract($param);
        $videoname=str_replace(' ', '', $filename);
        $param['video']->move($path, $videoname);
    }

    function upload_audio($param){
        extract($param);
        $audioname=str_replace(' ', '', $filename);
        $param['audio']->move($path, $audioname);
    }

    function get_increment_id($tableName){
        $getAutoIncId = DB::table('INFORMATION_SCHEMA.TABLES')
                ->select('AUTO_INCREMENT as id')
                ->where(['TABLE_SCHEMA'=>env('DB_DATABASE'),'TABLE_NAME'=>$tableName])
                ->get();
        return $getAutoIncId[0]->id;   
    }

    function change_status($param){
        $checkExisting = select(['table'=>$param['table'], 'column'=>'id', 'where'=>$param['where'] ]);
        if(!empty($checkExisting)){
            $update = update(['table'=>$param['table'], 'where'=>$param['where'], 'data'=>$param['data']]);
            if($update){
                $resp = array('status'=>1, 'msg'=> isset($param['msg']) ? $param['msg'] : 'Status Changed successfully.');
            }else{
                $resp = array('status'=>0,'msg'=>'Something went wrong.');
            }
        }else{
            $resp = array('status'=>0,'msg'=>'Something went wrong.');
        }
        return json_encode($resp);
    }

    function get_artist_name($param){
        if(isset($param['album_id'])){
            $album = Album::where('id', $param['album_id'])->select('song_list')->get();
        }
        else if(isset($param['radio_id'])){
            $radio = Radio::where('id', $param['radio_id'])->select('song_list')->get();
            $album = $radio;
        }
        $artist_name = '';
        if(!empty($album) || isset($param['is_audio'])){
            if(!isset($param['is_audio'])){
                $songs = json_decode(json_decode($album)[0]->song_list);
            }else{
                $songs = [$param['audio_id']];
            }
            if(!empty($songs)){
                $newArt = [];
                foreach($songs as $song){
                    $getArtist = Audio::where('id', $song)->select('artist_id')->get();
                    if(count($getArtist) > 0){
                        $artists = json_decode($getArtist[0]->artist_id);
                        if(!empty($artists)){
                            foreach($artists as $artist){
                                $newArt[] = $artist;
                            }
                        }
                    }
                }
                $artistIds = array_unique($newArt);
                if(!empty($artistIds)){
                    foreach($artistIds as $artist_id){
                        $artistName = Artist::where('id', $artist_id)->select('artist_name')->get();
                        if(count($artistName) > 0){
                            $artist_name .= $artistName[0]->artist_name.', ';
                        }
                    }
                }
            }
        }
        return ($artist_name != '' ? rtrim($artist_name,', ') : 'Unknown');
    }
    

    function audio_duration($param){
        $duration = '0:00';
        $media = MediaFile::open($param['path']);
        if ($media->isAudio()) {
            $audio = $media->getAudio();
            $durationMp3 = $audio->getLength().PHP_EOL;
            $durationMp3 = (float)$durationMp3;
            $duration = floor($durationMp3 / 60).':'.floor($durationMp3 % 60).PHP_EOL;
        }
        return $duration;
    }

    function multiple_audio_duration($param){
        $album_id = json_decode($param['list']);
        $countDuration = 0;
        foreach($album_id as $ids){
            $audioInfo = Audio::where('id', $ids)->select(['audio','id','aws_upload', 'audio_duration'])->get();
            if(sizeof($audioInfo) > 0){
                foreach($audioInfo as $information){
                    if(!empty($information)){
                        if($param['add']){
                            $dura = explode(':', $information->audio_duration);
                            $duration = implode('.', $dura);
                            $countDuration += floatval($duration);
                        }else{
                            $countDuration = $information->audio_duration;
                        }
                    }
                }
            }
        }
        if($param['add']){
            $newDura = explode('.', $countDuration);
            return implode(':', $newDura);
        }
        return $countDuration;
    }

    function audioDetail($param){
        $audioData = Audio::where('id', $param['songid'])->get();
        if(isset($param['image']) && sizeof($audioData) > 0){
            return url('public/images/audio/thumb/'.$audioData[0]->image);
        }else{
            return $audioData;
        }
    }

    function getFavDataId($param){
        if(isset(Auth::user()->id)){
            $getData = Favourite::where('user_id', Auth::user()->id)->get();
            if(sizeof($getData) > 0){
                $datas = json_decode($getData[0][ $param['column'] ]);
                
                if(!empty($datas)){
                    foreach($datas as $data){
                        if($data == $param[$param['column']]){
                            return 1;
                        }
                    }
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    function getArtistGenreName($param){
        $genre_name = 'Unknown';
        if(isset($param['genre_id']) && $param['genre_id'] != ''){
            $genre_data = ArtistGenre::where('id',$param['genre_id'])->get();
            if(!empty($genre_data)){
                $genre_name = $genre_data[0]->genre_name;
            }
        }
        return $genre_name;
    }

    function getCurrency($param){
        $getCurr = select(['column' => 'symbol', 'table' => 'currencies', 'where' => ['code' => $param['curr_code']], 'limit'=>1 ]);
        if(sizeof($getCurr) > 0){
            $currency = $getCurr[0]->symbol;
        }else{
            $currency = '$';
        }
        return $currency;
    }

    function getSongAWSUrlHtml($dataArr){
        $SrcDirectorty = env('AWS_DIRECTORY');
        $url = 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com/'.$SrcDirectorty;
        $files = Storage::disk('s3')->files($SrcDirectorty);
        foreach ($files as $file) {
            if(str_replace($SrcDirectorty.'/', '', $file) == $dataArr->audio){
                return url('download_audio?path='.urlencode($url.'/'.$dataArr->audio).'&name='.$dataArr->audio_slug);    
            }
        }
    }


    function dummyImage($type){
        if($type == 'slider'){
            return asset('public/images/sites/1660x800.png');
        }else if($type == 'testimonial'){
            return asset('public/images/sites/50x50.png');
        }else if($type == 'plan'){
            return asset('public/images/sites/200x200.png');
        }else if($type == 'blog'){
            return asset('public/images/sites/1050x700.png');
        }else{
            return asset('public/images/sites/500x500.png');
        }
    }

    function getYtPlaylistDetailById($id,$limit=null){
        if($limit == ''){
            $limit = 100;
        }
        return Youtube::getPlaylistItemsByPlaylistId($id,'',$limit);
    }

    function getSingleYtVideoById($id){
        return Youtube::getVideoInfo($id);
    }

    function getSelectedHomepage(){
        $is_dashboard = Settings::where('name', 'is_dashboard')->first(); 
        if(isset($is_dashboard) && !empty($is_dashboard->value) && $is_dashboard->value != 'dashboard')  
            return 'home2';
        else 
            return 'home';  
    }   
    
    function getDefaultCurrency($code = null){ 
        
        $defaultCode = '';
        $defaultCurrency = '';
        $dc = Settings::where('name', 'default_currency_id')->first();
        
        if(!empty($dc)){
            
            $defaultCurrency = Currency::where('id',$dc->value)->first();
            if(isset($code) && $code != ''){
                return $defaultCurrency->code;
            }else{
                return $defaultCurrency->symbol;
            }
        }else{
            return $defaultCurrency;
        }
        
    }
    
    // Convert currency to usd
    function trimPaymentAmount($amount){ 
        if(!empty($amount)){
            return (float) $amount;
        }else{
            return '0';
        }
    }
    
    function getArtistWithdrawBalance(){         
        
        $artistWithdrawBalance = 0;
        $artistAudioSalesData = select(['column' => ['users.name','audio.audio_title', 'admin_audio_payment.*'], 'table' => 'admin_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'admin_audio_payment.user_id'],['audio', 'audio.id', '=', 'admin_audio_payment.audio_id'] ] ])->where('artist_id',Auth::user()->id);
        $artistPaymentData = select(['column' => ['artist_audio_payment.*'], 'table' => 'artist_audio_payment', 'order' => ['id','desc']])->where('artist_id',Auth::user()->id)->sum('amount');     

        $wcArtistAmount = 0;

        if(isset($artistAudioSalesData) && !empty($artistAudioSalesData)){

            foreach ($artistAudioSalesData as $salesDetail) {
                if($salesDetail->commission_type == 'percent'){
                    $artistAmount = ($salesDetail->commission)*$salesDetail->amount/100;
                }elseif($salesDetail->commission_type == 'flat'){
                    $artistAmount = $salesDetail->amount-$salesDetail->commission;
                }
                $wcArtistAmount += $salesDetail->amount-$artistAmount;
                
            }
            $artistWithdrawBalance = $wcArtistAmount - $artistPaymentData;
        }
        $curr = getDefaultCurrency();
        if(isset($curr) && !empty($curr)){
            return $curr.$artistWithdrawBalance;
        }else{
            return '$'.$artistWithdrawBalance;
        }        
    }
    
?>