<?php

print token_replace('[node:field_teacher_title] @ [node:field-teacher-office:i18n-name]', ['node' => $variables['entity']]);
