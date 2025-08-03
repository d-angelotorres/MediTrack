<?php
session_start();
session_destroy();
header("Location: ../public/?page=login");
exit;
