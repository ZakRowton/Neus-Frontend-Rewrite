<?php
/**
 * NEUS Frontend Rewrite - Logout Handler
 */

logout();
redirect(pageUrl('/'), 'You have been signed out.', 'info');
?>
