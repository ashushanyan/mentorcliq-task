<?php

use app\controllers\ScoreController;
use app\validation\file\CheckCsvValidation;
use System\Http\Request;

class FileController
{
    public function checkCsv(Request $request): void
    {
        try {
            $validation = new CheckCsvValidation($request);
            if ($validation->check()) {
                $file = $request->files['document'];
                $this->fileUpload($file['tmp_name'], $file_path = "app/public/files/". str_replace(' ', "_", $file['name']));

                $csv = array_map("str_getcsv", file($file_path,FILE_SKIP_EMPTY_LINES));
                $keys = array_shift($csv);
                $body = [];
                foreach ($csv as $i=>$row) {
                    $body[$i] = array_combine($keys, $row);
                }
                $arr = [];
                for($i=0; $i < count($body) ;$i++) {
                    for($q=0; $q < $i ;$q++) {
                        $totalScore = PercentController::getEmployersScore($firstEmployee = $body[$i], $secondEmployee = $body[$q]);
                        if (isset($arr[$totalScore])){
                            array_push($arr[$totalScore], [
                                $body[$i]['Name'],
                                $body[$q]['Name'],
                            ]);
                        } else {
                            $arr[$totalScore][] = [
                                $body[$i]['Name'],
                                $body[$q]['Name'],
                            ];
                        }
                    }
                }
                krsort($arr, SORT_NUMERIC);
                $keys = array_keys($arr);

                $arrRes = [];
                if (count($arr[$keys[0]]) > 1) {
                    $score = $keys[0];
                    for($i=0; $i < count($arr[$keys[0]]) ;$i++) {
                        for($q=0; $q < $i ;$q++) {
                            $asdd = $arr[$keys[0]][$i] + $arr[$keys[0]][$q] ;
                            if (count(array_unique($asdd)) === 4) {
                                $arrRes[] = [
                                    $arr[$keys[0]][$i],
                                    $arr[$keys[0]][$q]
                                ];
                            }
                        }
                    }
                } else {
                    $score = ($keys[0] + $keys[1]) / 2 ;
                    foreach ($arr[$keys[1]] as $value) {
                        $arrRes[] = [
                            $arr[$keys[0]][0],
                            $value,
                        ];
                    }
                }

                $res=[];
                foreach ($arrRes as $val) {
                    $res['names'] = $val[0][0] . ' ' . $val[0][1] .', '. $val[1][0] . ' ' . $val[1][1];
                    $res['averageScore'] = $score;
                }

                echo json_encode(array_merge($res, [
                    'error' => false
                ]));
            } else {
                throw new Exception($validation->message);
            }
        } catch (Exception $e) {
            echo json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }


    public function fileUpload($file_tmp, $file_path)
    {
        move_uploaded_file($file_tmp, $file_path);
    }
}