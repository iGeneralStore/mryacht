<?php
	$data = c27()->merge_options([
			'footer_text'      => c27()->get_setting('footer_text', ''),
			'show_widgets'     => c27()->get_setting('footer_show_widgets', true),
			'show_footer_menu' => c27()->get_setting('footer_show_menu', true),
		], $data);
?>

<footer class="footer <?php echo esc_attr( ! $data['show_widgets'] ? 'footer-mini' : '' ) ?>">
	<div class="container">
		<?php if ( $data['show_widgets'] ): ?>
			<div class="row">
				<?php dynamic_sidebar('footer') ?>
			</div>
		<?php endif ?>

		<div class="row">
			<div class="col-md-12 reveal">
				<div class="footer-bottom">
					<div class="row">
						<div class="col-md-4 col-sm-12 col-xs-12 copyright">
							<p><?php echo str_replace( '{{year}}', date('Y'), $data['footer_text'] ) ?></p>
						</div>

						<?php if ($data['show_footer_menu']): ?>
							<div class="col-md-8 col-sm-12 col-xs-12 social-links">
								<?php wp_nav_menu([
									'theme_location' => 'footer',
									'container' => false,
									'menu_class' => 'main-menu',
									'items_wrap' => '<ul id="%1$s" class="%2$s social-nav">%3$s</ul>'
									]); ?>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
    // added by KH
    echo '<div style="width:100%; text-align:center;">';
        printf( __( '회사명 : (주)미스터요트 | 대표 : 이병철') );
    echo '<br>';
        printf( __( '사업자등록번호 : 424-81-01034') );
    echo '<br>';
        printf( __( '통신판매업 신고번호 : 제2018-부산남구-0293호') );
    echo '<br>';
        printf( __( '대표번호 : 010-8575-1354') );
    echo '<br>';
        printf( __( '부산광역시 남구 신선로 365, 부경대학교용당캠퍼스 제1공학관 304호(용당동)', 'mryacht' ) );
    echo '<H5>';
        printf( __( '상품에 대하여 민원, 환불 등 은 "(주)미스터요트" 에서 처리하며 모든 책임은 "(주)미스터요트" 에 있습니다.' ) );
    echo '</H5><H5>';
        printf( __( '민원 담당자 연락처: O1O-8575-1354(이병철)' ) );
    echo '</H5></div>';
?>

</footer>
