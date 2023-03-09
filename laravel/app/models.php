<?php
    use Illuminate\Support\Facades\DB;

    function select($param){
        $query = DB::table($param['table'])->select($param['column']);
        if(isset($param['where']) && !empty($param['where'])){
            $query->where($param['where']);
        }
        if(isset($param['orWhere']) && !empty($param['orWhere'])){
            $query->orWhere(['name'=>'preloader', 'name'=>'favicon']);
        }
        if(isset($param['join']) && !empty($param['join'])){
            if(sizeof($param['join']) == 1){
                $query->join($param['join'][0]);
            }
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
        $getData = select(['table'=>$param['table'],'column'=>$param['column'],'where'=>$param['column']]);
        if(!empty($getData)){
            if(isset($param['isImage']) && $param['isImage'] != ''){
                delete_file_if_exist($param['isImage'].$getData->image);
            }
            $delete = delete(['table'=>$param['table'], 'where'=>$param['where']]);
            if($delete){
                if($bulk)
                    return 1;
                else
                    $resp = array('status'=>1, 'msg'=>$param['msg']);
            }else{
                $resp = array('status'=>0, 'msg'=>'Something went wrong.');
            }
        }else{
            $resp = array('status'=>0, 'msg'=>'Something went wrong.');
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
                $checkDelete = singleDelete(['table'=>$param['table'], 'column'=>$param['column'],'where'=>['id'=>$getId]], 1);
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
            $resp = ['status'=>0, 'msg'=>'Something went wrong.'];
        }
        return $resp;
    }
?>