<?php

/**
 * Created by PhpStorm.
 * User: prosto
 * Date: 13.12.2017
 * Time: 10:38
 */
class PDFGenerator
{
    /**
     * @var \Mpdf\Mpdf
     */
    private  $mpdf;

    /**
     * @var AccreditationModule
     */
    private $module;

    /**
     * @var AccreditationUser
     */
    private $user;

    /**
     * @var AccreditationGroup
     */
    private $group;

    private $buffer;

    public function __construct(AccreditationUser $user,AccreditationGroup $group){
        $this->mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left'=>0,
            'margin_right'=>0,
            'margin_top'=>0,
            'margin_bottom'=>0,
            'margin_header'=>0,
            'margin_footer'=>0,
            'orientation' => 'P'
        ]);
        $this->mpdf->dpi = 300;
        $this->mpdf->img_dpi = 300;
        $this->user = $user;
        $this->group = $group;

        $this->mpdf->showImageErrors = true;
        $this->module = Yii::app()->getModule('accreditation');
    }


    public function generatePdf(){

        $templateDir = $this->module->getUploadTemplatePath().DIRECTORY_SEPARATOR.$this->group->id.DIRECTORY_SEPARATOR;
        $indexFile = $templateDir.'index.html';
        if(!file_exists($indexFile)) {
            throw new Exception('Не удалось найти файл index.html в шаблоне '.$this->group->name);
        }
        $this->buffer = file_get_contents($indexFile);//Yii::app()->getBaseUrl(true);
        $this->mpdf->basepath = $this->module->getWebTemplatePath().$this->group->id.'/';
        $this->parserHtml();
        $this->mpdf->SetJS('this.print(false);');
        $this->mpdf->WriteHTML($this->buffer);

        $this->mpdf->Output();
    }


    protected function parserHtml(){
        $fsize = 32;
        $lsize = 32;

        if(mb_strlen($this->user->firstname) > 10) {
            $fsize = 23;
        } else if(mb_strlen($this->user->firstname) > 12) {
            $fsize = 18;
        } else if(mb_strlen($this->user->firstname) > 15) {
            $fsize = 14;
        }

        if(mb_strlen($this->user->lastname) > 10) {
            $lsize = 23;
        } else if(mb_strlen($this->user->lastname) > 12) {
            $lsize = 18;
        } else if(mb_strlen($this->user->lastname) > 15) {
            $lsize = 14;
        }

        $size = $lsize < $fsize ? $lsize: $fsize;

        $this->buffer = str_replace('$firstname',$this->user->firstname,$this->buffer);
        $this->buffer = str_replace('$lastname',$this->user->lastname,$this->buffer);
        $this->buffer = str_replace('$photo',$this->user->photo,$this->buffer);


        $this->buffer = str_replace('$f_size',$size,$this->buffer);
        $this->buffer = str_replace('$l_size',$size,$this->buffer);

    }
}