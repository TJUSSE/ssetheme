<?php

function ds_intro_2col() {
  return array(
    'label' => t('介绍内容双栏布局'),
    'regions' => array(
      'left' => t('Left'),
      'right' => t('Right'),
    ),
    // Add this line if you're using DS 2.x for icon preview
    'image' => TRUE,
  );
}
