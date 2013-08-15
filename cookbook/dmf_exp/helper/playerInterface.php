<?php if (!defined('PmWiki')) exit();
function cmtSave($gc, $id, DanmakuBuilder $builder) {
    $id = basename($id);
    $_pagename = "DMR.{$gc->SUID}{$id}";
    $auth = 'edit';
    $page = @RetrieveAuthPage($_pagename, $auth, false, 0);
    if (!$page) {
        return false;
    }
    DanmakuTimeShift($page, $id, $builder);
    $page['text'] .= (string) $builder;
    WritePage($_pagename, $page);
    return true;
}

function DanmakuTimeShift(&$page, $dmid, DanmakuBuilder $builder) {
    global $TimeShiftDelta;
    if (empty($GLOBALS['EnableAutoTimeShift'])) return;
    
    $lastcommit = json_decode($page['LastCommit']);
    
    $isNull = ($lastcommit === null);
    
    if ($isNull) {
        $lastcommit = array(
            'sendtime' => time(),
            'playtime' => $builder->attrs[0]['playtime'],
            'count'    => 1);
    }
    
    $isTimeMatched = ($builder->attrs[0]['playtime'] == $lastcommit->playtime);
    
    if ($isTimeMatched) {
        $timeoffset = $TimeShiftDelta * $lastcommit->count;
        $builder->attrs[0]['playtime'] += $timeoffset;
        $lastcommit->count += 1;
    } else {
        $lastcommit->playtime = $builder->attrs[0]['playtime'];
        $lastcommit->count    = 0;
    }
    
    $lastcommit->sendtime = time();
    $page['LastCommit'] = json_encode($lastcommit);
}