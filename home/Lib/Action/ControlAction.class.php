<?php
class ControlAction extends Action {
  Public function relaOperate($rela) {
    if (M("Friends")->where(array('user' => session('ID'), 'friend_id' => $uid))->count()) {
      $rela['isFriend'] = 1;     
    } elseif ($Apply->where(array('user' => session('ID'), 'applicant_id' => $uid))->count()) {
      $rela['isFriend'] = -1;
    } else {
      $rela['isFriend'] = 0;
    }

    //确认是否已关注 
    if (M("Follow")->where(array('user' => session('ID'), 'follow_id' => $uid))->count()) {
      $rela['isFollow'] = 1;    
    } else {
      $rela['isFollow'] = 0;
    }    
  }
}