<?php
require_once( YL_ROOT . '/yl-functions.php');
require_once( YL_ROOT . '/fpdf/html_table.php');
require_once( YL_ROOT . '/tcpdf_min/tcpdf.php');
require_once( YL_ROOT . '/yl-meta-box-config.php');
require_once( YL_ROOT . '/signature/signature.php');

require_once( YL_ROOT . '/yl-email-settings.php');
require_once( YL_ROOT . '/yl-lease-settings.php');
require_once( YL_ROOT . '/yl-generate-lease-pdf.php');

require_once( YL_ROOT . '/register-new-clients.php');
require_once( YL_ROOT . '/lease-pdf-generate.php');
require_once( YL_ROOT . '/yl-ealry-vacate-settings.php');
require_once( YL_ROOT . '/yeager-reports.php');
require_once( YL_ROOT . '/yl-import.php');
require_once( YL_ROOT . '/yl-send-invoices.php');
require_once( YL_ROOT . '/yl-line-items-mod.php');
//require_once( YL_ROOT . '/yl-merchant-fees.php');
require_once( YL_ROOT . '/yl-schedule-email.php');

// My Acount AJAX
include_once( YL_ROOT . '/shortcodes/yl_my_account_ajax.php');

//subscription process
require_once( YL_ROOT . '/yl-subscription.php');


require_once( YL_ROOT . '/bad-debt-writeoff.php');

// Generate invoices + rollback
require_once( YL_ROOT . '/yl-generate-invoices.php');
require_once( YL_ROOT . '/yl-generate-invoices-rollback.php');

// Shortcodes
require_once( YL_ROOT . '/shortcodes/available-suites.php');
require_once( YL_ROOT . '/shortcodes/bm_lease_summary.php');
require_once( YL_ROOT . '/shortcodes/lease-client-sign.php');
require_once( YL_ROOT . '/shortcodes/my-lease-list.php');
require_once( YL_ROOT . '/shortcodes/lease-bm-sign.php');
require_once( YL_ROOT . '/shortcodes/manage-lease-list.php');
require_once( YL_ROOT . '/shortcodes/lease-summary-sign.php');
require_once( YL_ROOT . '/shortcodes/bm-lease-summary-sign.php');
require_once( YL_ROOT . '/shortcodes/vacate-notice.php');
require_once( YL_ROOT . '/shortcodes/yl_lease_checkout.php');
require_once( YL_ROOT . '/shortcodes/yl_my_account.php');
require_once( YL_ROOT . '/shortcodes/yl_standalone_invoices.php');
require_once( YL_ROOT . '/shortcodes/yl_bm_invoice_manage.php');
require_once( YL_ROOT . '/shortcodes/yl_bm_lease_list.php');
require_once( YL_ROOT . '/shortcodes/yl_tenant_information.php');
require_once( YL_ROOT . '/shortcodes/yl_checkout_url.php');
require_once( YL_ROOT . '/shortcodes/automated-directory.php');
require_once( YL_ROOT . '/shortcodes/yl_bm_prospects.php');
require_once( YL_ROOT . '/shortcodes/yeager-lease-new-rate.php');

