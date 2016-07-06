<style type='text/css'>
.bbit_metabox{overflow:auto;padding:10px 10px}
.bbit_metabox_field{margin-bottom:15px;width:100%;overflow:hidden}
.bbit_metabox_field label{font-weight:bold;float:left;width:15%}
.radio{}
.bbit_metabox_field .field{float:left;width:75%}
.bbit_metabox_field input[type=text], .bbit_metabox_field textarea {width:100%}
.tabs{position:relative;min-height:300px;clear:both;margin:25px 0}
.tab{float:left}
.label{background:#eee;padding:10px;border:1px solid #ccc;margin-left:-1px;position:relative;left:1px}
.input[type=radio]{display:none}
.bbit_metabox{position:absolute;top:28px;left:0;background:white;right:0;bottom:0;border:1px solid #ccc}
.input[type=radio]:checked ~ label{background:white;border-bottom:1px solid white;z-index:2}
.input[type=radio]:checked ~ .label ~ .bbit_metabox{z-index:1}
</style>

<div class="tabs">

<div class="tab">
<input class="input" type="radio" id="tab-1" name="tab-group-1" checked>
<label class="label" for="tab-1">Cơ bản</label>
<div class="bbit_metabox">
<?php
$this->text('heading', 'Tiêu đề H1');
$this->select('search_keyword','Giao diện từ khóa',array('' => 'Không sử dụng','game' => 'Từ khóa về Game Online','android' => 'Từ khóa về Android','ios' => 'Từ khóa về iOS', 'windowsphone'=>'Từ khóa Windows Phone', 'java' => 'Từ khóa về Java', 'story' => 'Từ khóa về Sách - Truyện'));	
?>
</div>   
</div>    

<div class="tab">
<input class="input" type="radio" id="tab-2" name="tab-group-1">
<label class="label" for="tab-2">Tùy chọn Apps</label>
<div class="bbit_metabox">
<?php
$this->text('thumb', 'Icon ứng dụng');
$this->text('phathanh', 'Phát hành');
$this->text('file_size', 'Dung lượng');			
$this->text('support', 'Hỗ trợ');
$this->text('link_download', 'File link');
?>
</div>       
</div> 

<div class="tab">
<input class="input" type="radio" id="tab-3" name="tab-group-1">
<label class="label" for="tab-3">Tùy chọn Story</label>
<div class="bbit_metabox">
<?php
$this->text('author2', 'Tác giả');
?>
</div>   
</div>

<div class="tab">
<input class="input" type="radio" id="tab-4" name="tab-group-1">
<label class="label" for="tab-4">Tùy chọn Music</label>
<div class="bbit_metabox">
<?php
$this->text('singer', 'Ca Sỹ');
$this->text('author', 'Sáng tác');
$this->text('album', 'Album');
?>
</div>
</div>
        
<div class="tab">
<input class="input" type="radio" id="tab-5" name="tab-group-1">
<label class="label" for="tab-5">Tùy chọn Video</label>
<div class="bbit_metabox">
<?php
$this->text('video', 'Video File');
$this->text('video_time', 'Thời lượng');
?>
</div>
</div>

</div>