<?php

namespace plum\lib;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\Model;

class Excel
{
    private $config = [];

    public function __construct($config = [])
    {
        if (!empty($config)) {
            $this->config = array_merge($this->config, $config);
        }
        $this->sheet = new Spreadsheet();
    }

    /**
     * 设置字段 [['label'=>'名称','field'=>'字段','width'=>'宽度（单位厘米）']]
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月20日 14:28
     */
    public function setField($data)
    {
        $this->config['field'] = $data;
        return $this;
    }

    /**
     * 设置字段
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月20日 14:38
     */
    public function setTitle($title, $color = null, $backgroundColor = null)
    {
        $this->config['title'] = [
            'label'            => $title,
            'color'            => $color ?? 'FFFFFF',
            'background_color' => $backgroundColor ?? 'ED7D31',
        ];
        return $this;
    }

    /**
     * 设置内容
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月20日 14:38
     */
    public function setContent($data)
    {
        if ($data instanceof Model) {
            $this->config['content'] = $data->plumSearch()
                ->plumOrder()
                ->select()
                ->toArray();
        } else {
            $this->config['content'] = $data;
        }
        return $this;
    }

    /**
     * 获取配置
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月20日 14:38
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 表格回调
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月20日 14:39
     */
    public function sheetCallback($callback)
    {
        call_user_func_array([$callback], [$this->sheet->getActiveSheet()]);
        return $this;
    }

    private function fillData()
    {
        $data = [];
        //填充标题
        array_push($data, [$this->config['title']['label']]);
        //填充字段
        array_push($data, array_map(function ($item) {
            return $item['label'];
        }, $this->config['field']));
        //填充内容
        $data = array_merge($data, array_map(function ($row) {
            return array_map(function ($item) use ($row) {
                return $row[$item['field']] ?? '';
            }, $this->config['field']);
        }, $this->config['content']));
        $this->sheet->getActiveSheet()->fromArray($data);
    }

    private function setStyle()
    {
        //设置列表对齐
        $this->sheet->getActiveSheet()
            ->getStyleByColumnAndRow(1, 2, count($this->config['field']), count($this->config['content']) + 2)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //设置所有的边框
        $this->sheet->getActiveSheet()
            ->getStyleByColumnAndRow(1, 1, count($this->config['field']), count($this->config['content']) + 2)
            ->applyFromArray([
                'borders' => [
                    'inside'  => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                        'color'       => ['argb' => 'FF' . $this->config['title']['background_color']],
                    ],
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color'       => ['argb' => 'FF' . $this->config['title']['background_color']],
                    ],
                ]
            ]);
        //设置标题合并
        $this->sheet->getActiveSheet()
            ->mergeCellsByColumnAndRow(1, 1, count($this->config['field']), 1);
        //设置标题高度
        $this->sheet->getActiveSheet()
            ->getRowDimension(1)
            ->setRowHeight(38);
        //设置标题样式
        $this->sheet->getActiveSheet()
            ->getStyleByColumnAndRow(1, 1)
            ->applyFromArray([
                'font'      => [
                    'bold'  => true,
                    'size'  => 20,
                    'color' => [
                        'argb' => 'FF' . $this->config['title']['color']
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'fill'      => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FF' . $this->config['title']['background_color'],
                    ],
                ]
            ]);
        //设置字段的高度
        $this->sheet->getActiveSheet()
            ->getRowDimension(2)
            ->setRowHeight(20);
        //设置字段的样式
        $this->sheet->getActiveSheet()
            ->getStyleByColumnAndRow(1, 2, count($this->config['field']), 2)
            ->applyFromArray([
                'fill'      => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFd9d9d9',
                    ],
                ],
                'font'      => [
                    'bold' => true,
                ],
                'borders'   => [
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color'       => ['argb' => 'FF' . $this->config['title']['background_color']],
                    ],
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                          'color'       => ['argb' => 'FF' . $this->config['title']['background_color']],
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color'       => ['argb' => 'FF' . $this->config['title']['background_color']],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ]
            ]);
        // 设置列表宽度
        foreach ($this->config['field'] as $k => $v) {
            if (isset($v['width'])) {
                $this->sheet->getActiveSheet()
                    ->getColumnDimensionByColumn($k + 1)
                    ->setWidth($v['width']);
            }
        }


    }

    /**
     * 下载
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月20日 18:51
     */
    public function download()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$this->config['title']['label'].'.xlsx"');
        header('Cache-Control: max-age=0');
        //首先整理数据，进行填充
        $this->fillData();
        //进行格式处理
        $this->setStyle();
        $writer = new Xlsx($this->sheet);
        $writer->save('php://output');
        die();
    }
}
