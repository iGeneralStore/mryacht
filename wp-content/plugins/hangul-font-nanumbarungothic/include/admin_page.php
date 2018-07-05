<?php
$_footer_help = '<i>Copyright (c) 2014 <a href="http://www.iamgood.co.kr" target="_blank">iamgood</a> Corporation. All rights reserved.</i>';
$default_tag = 'h1, h2, h3, h4, h5, h6, li, p, span, label, input';
if(!trim($this->options['tag']) && $this->options['active'] == '') $this->options['tag'] = $default_tag;
?>
<div class='wrap'>
    <h2><?= $this->title . ' - ' . $this->fontName ?></h2>
    <h2 class="nav-tab-wrapper">
        <a id='setting-tab' href="#" class="nav-tab">기본설정</a>
    </h2>
    <div id="setting" class="group">
        <form method="post" action="options.php">
            <?php settings_fields($this->settings_group); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">폰트 설정 활성화</th>
                    <td class="token_input">
                        <input type="checkbox" name="<?php echo $this->settings_field; ?>[active]"
                               value ="y" <?php if($this->options['active'] == 'y') echo 'checked'; ?> /> 활성
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">폰트 동기화</th>
                    <td class="token_input">
                        <input type="checkbox" name="<?php echo $this->settings_field; ?>[sync]"
                               value ="y" <?php if($this->options['sync'] == 'y') echo 'checked'; ?> /> 폰트 깜박임 방지 (페이지 로딩속도가 느려질 수 있습니다)
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">폰트적용 태그입력</th>
                    <td>
                        <div class='css_input'>
                            <b>등록방법</b><br />
                            일반태그 : <b>h3, h2, div</b><br />
                            ID 적용 :  <b>#content, #footer.div</b><br />
                            클래스 적용 : <b>.board, div.footer</b><br />
                            <input type="button" class="button" id="btn_default_tag" value="기본태그 설정"  />
                        </div>
                        <textarea class="font_tag" id="textarea_default_tag" name="<?php echo $this->settings_field; ?>[tag]"><?php echo htmlspecialchars($this->options['tag']) ?></textarea><br />
                        <input type="checkbox" name="<?php echo $this->settings_field; ?>[imp]"
                               value ="y" <?php if($this->options['imp'] == 'y') echo 'checked'; ?> /> !important 사용<br /> &nbsp; &nbsp; &nbsp;
                        (일부 영역에 폰트가 적용되지 않는 경우 체크 - 페이지 로딩속도가 느려질 수 있습니다.)
                        
                        <div style='margin-top:30px;text-decoration:underline'>테마 및 다른 플러그인에서 여러 웹폰트를 사용할 경우 페이지 로딩속도가 저하되거나 적용이<br />일부 제한될 수 있습니다.</div>
                        
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">추가설정 CSS</th>
                    <td>
                        <textarea class="font_css_area" name="<?php echo $this->settings_field; ?>[css]"><?php echo htmlspecialchars($this->options['css']) ?></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
            <hr>
        </form>
        <textarea id="hangulfont_default_tag"><?php echo htmlspecialchars($default_tag) ?></textarea>
    </div>
</div>
<?php echo $_footer_help; ?>
