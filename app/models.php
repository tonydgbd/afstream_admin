<?php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;

    function select($param){
        $query = DB::table($param['table'])->select($param['column']);
        if(isset($param['where']) && !empty($param['where'])){
            $query->where($param['where']);
        }
        if(isset($param['orWhere']) && !empty($param['orWhere'])){
            $query->orWhere($param['orWhere']);
        }
        if(isset($param['join']) && !empty($param['join'])){
            foreach($param['join'] as $join){
                $query->leftjoin($join[0], $join[1], $join[2], $join[3]);
            }
        }
        if(isset($param['order']) && !empty($param['order'])){
            $query->orderBy($param['order'][0],$param['order'][1]);
        }
        if(isset($param['single']) && $param['single'] == 1){
            return $query->first();
        }
        else
            return $query->get();
            
    }

    function insert($param){
        $id = DB::table($param['table'])->insertGetId($param['data']);
        return $id;
    }

    function update($param){
        $update = DB::table($param['table'])->where($param['where'])->update($param['data']);
        return $update;
    }

    function updateOrInsert($param){
        $updateInsert = DB::table($param['table'])->updateOrInsert($param['data'][0], $param['data'][1]);
        return $updateInsert;
    }

    function delete($param){
        $delete = DB::table($param['table'])->where($param['where'])->delete();
        return $delete;
    }

    function singleDelete($param, $bulk=''){
        $getData = select(['table'=>$param['table'],'column'=>$param['column'],'where'=>$param['where']]);
        if(!empty($getData)){
            if(isset($param['isImage']) && $param['isImage'] != '' && $getData[0]->image !=''){
                delete_file_if_exist($param['isImage'].$getData[0]->image);
            }
            
            if(isset($param['aws_upload']) && $param['aws_upload'] == '1' && $getData[0]->aws_upload == 1){
                if($exists = Storage::disk('s3')->exists('audios/'.$getData[0]->audio)){
                    Storage::disk('s3')->delete('audios/'.$getData[0]->audio);
                }
            }
            $delete = delete(['table'=>$param['table'], 'where'=>$param['where']]);
            if($delete){
                if($bulk)
                    return 1;
                else
                    $resp = array('status'=>1, 'msg'=>$param['msg']);
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
            }
        }else{
            $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
        }
        return json_encode($resp);
    }

    function bulkDeleteData($param){
        $cnt=1;
        foreach($param['request']['checked'] as $checked){
            $getData = select(['table'=>$param['table'], 'column'=>$param['column'],'where'=>['id'=>$checked] ]);
            $msg = 'User deleted successfully.';
            if(!empty($getData)){
                $getId = $getData[0]->id;
                $dataArr = ['table'=>$param['table'], 'column'=>$param['column'],'where'=>['id'=>$getId]];
                if(isset($param['isImage']) && $param['isImage'] != ''){
                    $dataArr['isImage'] = $param['isImage'];
                }
                if(isset($param['aws_upload']) && $param['aws_upload'] != ''){
                    $dataArr['aws_upload'] = $param['aws_upload'];
                }
                $checkDelete = singleDelete($dataArr, 1);
                if($checkDelete == '1')
                    $status = 1;
                else
                    $status = 0;
                $cnt++;
            }else
                $status = 0;
        }
        if($status == 1){
            $resp = ['status'=>1, 'msg'=>$param['msg']];
        }else{
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        }
        return $resp;
    }
?>