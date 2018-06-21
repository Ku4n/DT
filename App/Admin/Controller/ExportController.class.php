<?php
/**
 * Created by PhpStorm.
 * User: 赵家宽
 * Date: 2018/6/20
 * Time: 16:50
 */

namespace Admin\Controller;

use Think\Controller;

class ExportController extends Controller{

    public function index(){

        echo 123;
    }

    public function export(){

        $db = M('user');
        //$start_time = strtotime(I('param.start_time'));//前端返回時間後，再篩選
        //$end_time = strtotime(I('param.end_time'));
        $start_time = '1522512000';
        $end_time = '1525017600';
        $map['sign_time'] = array(array('EGT' , "$start_time") , array('LT' , "$end_time"));
        $map['del'] = false;
        if($start_time && $end_time){
            $xlsData = $db -> table('signup S') ->
            join('user U on U.userId = S.userId') ->
            field('U.id , U.username , S.time') -> where($map) -> group('S.id') -> select();
        }else{
            return false;
        }
        $xlsName = '报名名单';
        $xlsCell = array(
            array('id','用户ID'),
            array('username','用户名称'),
            array('time','报名时间')
        );
        $this -> exportExcel($xlsName,$xlsCell,$xlsData);
        exit();
    }

    public function exportExcel($expTitle, $expCellName, $expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = date('报名名单');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        //$objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle);
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }

        for($i = 0; $i < $dataNum; $i++){
            for($j = 0; $j < $cellNum; $j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}