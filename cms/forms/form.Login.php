<?php
    return array(
        'fields' => array(
            'login'=>'text',
            'pass'=>'text',
        ),
        'onCheck'=>function(CoreInterface $core, array $data) {
            
            $producer = new Producer($core);

            $user = $producer->
                produce('User')->
                getByParams(
                    array(
                        'name'=>$data['login'],
                        'pass'=>$data['pass'],
                    )
                );
            if ($user) {
                $_SESSION['user']=$user;
                return array('result'=>'ok','uri'=>'/admin/');
            }
              
            return array('result'=>'check','mark'=>'login_login','msg'=>'No user');
        },
    );