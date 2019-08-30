<?php

wp_enqueue_style('select2_style');
wp_enqueue_script('select2_script');
wp_enqueue_script('custom_script');

echo EDD_FES()->forms->render_submission_form();