<?php

/*
  Plugin Name: 한글폰트 나눔바른고딕
  Plugin URI: http://www.iamgood.co.kr
  Description: 한글폰트 나눔바른고딕
 * This is a plugin for built in board on the website
  Version: 1.0
  Author: iamgood
  Author URI: http://www.iamgood.co.kr
 */

/* Copyright 2014 Hangul Font nanumgothic (email : consult@iamgood.co.kr)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('hf_Main_class')) :
    include_once 'class/hf_class.php';
    include_once 'class/hf_lib_class.php';
    include_once 'class/hf_dir_class.php';
    include_once 'class/hf_tpl_class.php';
endif;

hf_Main_class::instance('나눔바른고딕', 'nanumbarungothic', __FILE__);
?>
