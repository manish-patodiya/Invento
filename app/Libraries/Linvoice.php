<?php
namespace App\Libraries;

class Linvoice
{

    public function __construct()
    {
        $this->session = service('session');
        $this->parser = service('parser');
        $this->TransactionModel = model('TransactionModel');
    }

    public function view_invoice($type_id, $inv_id, $print = false)
    {
        switch ($type_id) {
            case 1:
                return $this->est_invoice($inv_id, $print);
                break;
            case 2:
                return $this->CPO_inv($inv_id, $print);
                break;
            case 3:
                return $this->PI_inv($inv_id, $print);
                break;
            case 4:
                return $this->VPO_inv($inv_id, $print);
                break;
            case 5:
                return $this->GRN_inv($inv_id, $print);
                break;
            case 7:
                return $this->DC_inv($inv_id, $print);
                break;
            case 8:
                return $this->TI_inv($inv_id, $print);
                break;
            case 9:
                return $this->RDN_inv($inv_id, $print);
                break;
            case 10:
                return $this->RCN_inv($inv_id, $print);
                break;
            case 11:
                return $this->PR_inv($inv_id, $print);
                break;
            case 12:
                return $this->PV_inv($inv_id, $print);
                break;
        }
    }

    public function est_invoice($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;

        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div($inv_data);
        $grand_total_div = $this->make_grand_total_div($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/EST.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 39;
        $middle_pages_row_count = 44;
        $last_page_row_count = 42;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    private function add_serial_no($data)
    {
        foreach ($data as $key => $value) {
            $data[$key]->sn = ($key + 1) . '.';
            if (strlen($data[$key]->product_name) > 15) {
                $data[$key]->product_name = substr($data[$key]->product_name, 0, 15) . '...';
            }
            $data[$key]->reference_date = date('d M Y', strtotime($value->reference_date));
            $data[$key]->shipping_charges = ' ₹' . $value->shipping_charges;
            $data[$key]->round_of = ' ₹' . $value->round_of;
            $data[$key]->payable_amt = ' ₹' . $value->payable_amt;
            $data[$key]->hsn_code = 'HSN/SAC code: ' . $value->hsn_code;
            if ($value->discount_type == 2) {
                $data[$key]->discount_amt = $value->discount_amt . ' %';
            } elseif ($value->discount_type == 1) {
                $data[$key]->discount_amt = $value->discount_amt . ' ₹';
            }
        }
        return $data;
    }

    public function CPO_inv($inv_id, $print)
    {

        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div($inv_data);
        $grand_total_div = $this->make_grand_total_div($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/CPO.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 39;
        $middle_pages_row_count = 44;
        $last_page_row_count = 42;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    public function PI_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div_PInvoice($inv_data);
        $grand_total_div = $this->make_grand_total__PInvoic_div($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/PInvoice.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 35;
        $middle_pages_row_count = 34;
        $last_page_row_count = 32;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_PInvo_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    public function VPO_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div($inv_data);
        $grand_total_div = $this->make_grand_total_div($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/VPO.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 39;
        $middle_pages_row_count = 44;
        $last_page_row_count = 42;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    //.....................................
    public function GRN_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div($inv_data);
        $grand_total_div = $this->make_grand_total_div($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/test.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 39;
        $middle_pages_row_count = 44;
        $last_page_row_count = 42;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    //parse data in all invoice.............................
    public function parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row = '')
    {
        $parse = $this->parser->setData($data)->renderString($tmplt);
        return str_replace(['{grand_total_div}', '{header}', '{footer}', '{supplier_info_div}', '{grand_total_row}'], [$grand_total_div, $header, $footer, $supplier_info_div, $grand_total_row], $parse);
    }
    // this fuunction  are used for 4 invoices( EST Invoice, CPO Inovice, PInvoice and  VPO Invoice) ..................
    private function make_tax_table($inv_data)
    {
        $taxes = json_decode($inv_data->taxes_json);
        $gst_type = [];
        $gst1 = "074554";
        $gst1 = substr($inv_data->from_gst_no, 0, 2);
        $gst2 = substr($inv_data->to_gst_no, 0, 2);
        $total_tax = $inv_data->total_gst_tax;

        if ($gst1 != $gst2) {
            $gst_type[] = 'IGST';
        } else {
            if (in_array($gst1, UT_CODES)) {
                $gst_type = ['CGST', 'UTGST'];
            } else {
                $gst_type = ['CGST', 'SGST'];
            }
        }
        $thead = '<table border="1" width="361" cellpadding="0" id="table6" style="border-collapse: collapse;height:100%;" bordercolor="#000000" class="table table-bordered"><tr><td align="left" valign="top"><b><font face="Calibri" size="2">TAX</font></b></td>';
        foreach (TAXES as $tax) {
            $thead .= '<td align="right" valign="top"><b><font face="Calibri" size="2">' . $tax . '%</font></b></td>';
        }
        $thead .= '<td align="right" valign="top"><b><font face="Calibri" size="2">Total</font></b></td></tr>';

        $tbody = '';
        foreach (GST_NAMES as $name) {
            $tbody .= '<tr><td align="left" valign="top"><b><font face="Calibri" size="2">' . $name . '</font></b></td>';
            foreach ($taxes as $amt) {
                if (in_array($name, $gst_type)) {
                    if (count($gst_type) == 1) {
                        $tbody .= '<td align="right" valign="top"><font face="Calibri" size="2">' . to_fixed($amt) . '</font></td>';
                    } else if (count($gst_type) == 2) {
                        $tbody .= '<td align="right" valign="top"><font face="Calibri" size="2">' . to_fixed($amt / 2) . '</font></td>';
                    }
                } else {
                    $tbody .= '<td align="right" valign="top"><font face="Calibri" size="2">0.00</font></td>';
                }
            }
            if (in_array($name, $gst_type)) {
                if (count($gst_type) == 1) {
                    $tbody .= '<td align="right" valign="top"><b><font face="Calibri" size="2">' . to_fixed($total_tax) . '</font></b></td>';
                } else if (count($gst_type) == 2) {
                    $tbody .= '<td align="right" valign="top"><b><font face="Calibri" size="2">' . to_fixed($total_tax / 2) . '</font></b></td>';
                }
            } else {
                $tbody .= '<td align="right" valign="top"><b><font face="Calibri" size="2">0.00</font></b></td>';
            }
            $tbody .= '</tr>';
        }

        $tfoot = '<tr><td align="left" valign="top"><b><font face="Calibri" size="2">Total</font></b></td>';
        foreach ($taxes as $amt) {
            $tfoot .= '<td align="right" valign="top"><b><font face="Calibri" size="2">' . to_fixed($amt) . '</font></b></td>';
        }
        $tfoot .= '<td align="right" valign="top"><b><font face="Calibri" size="2">' . to_fixed($total_tax) . '</font></b></td></tr>';
        $tfoot .= '</table>';

        return $thead . $tbody . $tfoot;
    }

    private function make_grand_total_div($inv_data)
    {
        $shi_char = " ₹" . $inv_data->shipping_charges;
        $round_of = " ₹" . $inv_data->round_of;
        $payable_amt = " ₹" . $inv_data->payable_amt;

        $tbl_tax = $this->make_tax_table($inv_data);
        $grand_total_div =
        '<tr>
            <td width="450" rowspan="2" height="50" valign="top">
                <font face="Calibri" size="2">Notes :<b>' . $inv_data->notes . '</b></font>
            </td>
            <td width="300" height="114" rowspan="5" align="justify" style="padding: 0;">
                ' . $tbl_tax . '
            </td>
            <td width="500" height="15" rowspan="2" valign="top" align="right">
                <font face="Calibri">
                    <font size="2">Shipping Charges :</font>
                    <b><font size="2">' . $shi_char . '</b></font>
                </font>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td rowspan="3">' . $inv_data->details . '</td>
            <td width="378" height="15" rowspan="2" valign="top" align="right">
                <font face="Calibri">
                    <font size="2">Round Of : </font>
                    <b><font size="2">' . $round_of . '</font></b>
                </font>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td width="378" height="17" valign="top" align="right">
                <font face="Calibri">
                    <font size="2">Net Total : </font>
                    <b><font size="2">' . $payable_amt . '</font></b>
                </font>
            </td>
        </tr>';

        return $grand_total_div;
    }

    private function make_supplier_info_div($inv_data)
    {
        $originalDate = $inv_data->date;
        $vpo_Dt = date('d M Y', strtotime($originalDate));

        $originalDate = $inv_data->reference_date;
        $Ref_Dt = date('d M Y', strtotime($originalDate));

        $supplier_info_div =
        '<tr>
                <td width="378" height="100" rowspan="2" valign="top" align="justify">
                    <font face="Calibri" size="2">
                        <b>Supplier :<br>' . $inv_data->branch_name . '</b><br>
                    </font>
                    <font size="2" face="Calibri">' . $inv_data->branch_address . '</font>
                </td>
                <td width="167" height="34">
                    <font face="Calibri" size="2">Order No:</font>
                    <font face="Calibri"> <b>' . $inv_data->invoice_prefix_id . '</b></font>
                </td>
                <td width="191" height="34">
                    <font face="Calibri" size="2">Date : <b>' . $vpo_Dt . '</b></font>
                </td>
            </tr>
            <tr>
                <td width="167" height="25" align="justify" valign="top">
                    <font face="Calibri" size="2">Your Ref :<b>
                            </b></font><b>
                            <font size="2" face="Calibri">' . $inv_data->reference_no . '&nbsp; </font>
                        </b>
                </td>
                <td width="191" height="25" align="justify" valign="top">
                    <font size="2" face="Calibri">Date :<b> </b></font><b>
                            <font size="2" face="Calibri">' . $Ref_Dt . '</font>
                        </b>
                </td>
            </tr>
            ';

        return $supplier_info_div;
    }

    private function get_grand_total_row($inv_data)
    {
        $grand_total_row =
        '<tr>
                <td colspan="4" class="text-end"><b>
                            <font face="Calibri" size="2">Total:</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->total_discount . ' ₹</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->total_taxable_amt . '</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2"></font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->grand_total . '</font>
                        </b></td>
            </tr>';
        return $grand_total_row;
    }

    // all invoices are used for dufault header.............
    public function insert_default_header($branch_id)
    {
        $info = model('Branches')->get_branch_info($branch_id);
        $data = [
            'logo' => $info->logo,
            'company_name' => $info->company,
            'branch_name' => $info->name,
            'branch_address' => $info->address,
            'phone' => $info->phone,
            'email' => $info->email,
            'website_url' => $info->website_url,
            'gst_no' => $info->gst_no,
            'cin_no' => $info->cin,
            'iec_code' => $info->iec_code,
        ];
        $tmplt = file_get_contents(base_url('public/trans_printing_format/header.htm'));
        $header = $parse = $this->parser->setData($data)->renderString($tmplt);

        $trans_types = model('TransactionModel')->get_transaction_type_list();
        $insert_data = [];
        foreach ($trans_types as $type) {
            $insert_data[] = [
                'branch_id' => $branch_id,
                'trans_type_id' => $type->id,
                'sub_type_id' => 1,
                'title' => $type->title . ' Header',
                'content' => str_replace('{invoice_type}', strtoupper($type->title), $header),
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        $res = model('Templates')->insert_batch($insert_data);
    }
    //...........................................

    // PInvoice  Details call for 3 functiones ( make_supplier_info_div_PInvoice, make_grand_total__PInvoic_div,)........................
    private function make_supplier_info_div_PInvoice($inv_data)
    {
        $originalDate = $inv_data->date;
        $vpo_Dt = date('d M Y', strtotime($originalDate));

        $originalDate = $inv_data->reference_date;
        $Ref_Dt = date('d M Y', strtotime($originalDate));

        if ($inv_data->branch_adrs_id == $inv_data->sec_branch_adrs_id) {
            $sec_bran_adrs = "";
            $sec_shipp_bran_adrs = "";
            $name = '';
        } else if ($inv_data->branch_adrs_id != $inv_data->sec_branch_adrs_id) {
            $name = 'Shipping Address';
            $sec_bran_adrs = $inv_data->sec_branch_name;
            $sec_shipp_bran_adrs = $inv_data->sec_branch_address;
        }

        $supplier_info_div =
        ' <tr>
            <td width="378" height="82" rowspan="4" valign="top" align="justify">
                <b>
                    <font size="2" face="Calibri">Billing Address :<br>
                        M/s.' . $inv_data->branch_name . '
                        <br>
                    </font>
                </b>
                <font size="2" face="Calibri">' . $inv_data->branch_address . '</font><br>
            </td>
            <td width="217" height="24">
                <font face="Calibri" size="2">Invoice No: </font>
                <font face="Calibri"> <b>' . $inv_data->invoice_prefix_id . '</b></font>
            </td>
            <td width="114" height="24">
                <font face="Calibri" size="2">Date: <b>' . $vpo_Dt . '</b></font>
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
         <tr>
                         <td width="167" height="25" align="justify" valign="top">
                    <font face="Calibri" size="2">Your Ref :<b>
                            </b></font><b>
                            <font size="2" face="Calibri">' . $inv_data->reference_no . '&nbsp; </font>
                        </b>
                </td>
                <td width="191" height="25" align="justify" valign="top">
                    <font size="2" face="Calibri">Date :<b> </b></font><b>
                            <font size="2" face="Calibri">' . $Ref_Dt . '</font>
                        </b>
                </td>
         </tr>
        <tr>
             <td width="378" height="55"  valign="top" align="justify">
              <font face="Calibri" size="2"><b>' . $name . '<br>' . $sec_bran_adrs . '
              </b></br></font>
              <font size="2" face="Calibri">' . $sec_shipp_bran_adrs . '&nbsp; </font>
                         </td>
              <td width="361" colspan="2" height="23">
              <font face="Calibri" size="2">
                <b>' . $inv_data->details . '&nbsp;</b>
               </font>
            </td>
         </tr>
            ';

        return $supplier_info_div;
    }

    private function make_grand_total__PInvoic_div($inv_data)
    {
        $shi_char = " ₹" . $inv_data->shipping_charges;
        $round_of = " ₹" . $inv_data->round_of;
        $payable_amt = " ₹" . $inv_data->payable_amt;

        $tbl_tax = $this->make_tax_table($inv_data);
        $grand_total_div =
        '<tr>
            <td width="50" rowspan="2" height="50" valign="top">
                <font face="Calibri" size="2">sRupees :<b>' . $inv_data->notes . '</b></font>
            </td>
            <td width="300" height="114" rowspan="5" align="justify" style="padding: 0;">
                ' . $tbl_tax . '
            </td>
            <td width="500" height="15" rowspan="2" valign="top" align="right">
                <font face="Calibri">
                    <font size="2">Shipping Charges :</font>
                    <b><font size="2">' . $shi_char . '</b></font>
                </font>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td rowspan="3">' . $inv_data->notes . '</td>
            <td width="378" height="15" rowspan="2" valign="top" align="right">
                <font face="Calibri">
                    <font size="2">Round Of : </font>
                    <b><font size="2">' . $round_of . '</font></b>
                </font>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td width="378" height="17" valign="top" align="right">
                <font face="Calibri">
                    <font size="2">Net Total : </font>
                    <b><font size="2">' . $payable_amt . '</font></b>
                </font>
            </td>
        </tr>';

        return $grand_total_div;
    }
    private function get_grand_total_PInvo_row($inv_data)
    {
        $grand_total_row =
        '<tr>
                <td colspan="4" class="text-end"><b>
                            <font face="Calibri" size="2">Total:</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->total_discount . ' ₹</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->total_taxable_amt . '</font>
                        </b></td>
                     </tr>';
        return $grand_total_row;
    }

    //VPO call for details ................
    public function DC_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div_vpo($inv_data);
        $grand_total_div = $this->make_grand_total_div_vpo($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/Dc.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 35;
        $middle_pages_row_count = 40;
        $last_page_row_count = 38;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }
    // call private functiones(make_grand_info_div_vpo,make_grand_total_div_vpo).........
    private function make_supplier_info_div_vpo($inv_data)
    {
        $originalDate = $inv_data->date;
        $vpo_Dt = date('d M Y', strtotime($originalDate));

        $originalDate = $inv_data->reference_date;
        $Ref_Dt = date('d M Y', strtotime($originalDate));

        $supplier_info_div = '<tr>
        <td width="300" rowspan="4" valign="top" align="justify">
            <b>
                <font face="Times New Roman">To,<br>
                    M/s.
                    ' . $inv_data->branch_name . '<br>
                </font>
            </b>
            <font face="Times New Roman">' . $inv_data->branch_address . '</font>
        </td>
        <td width="217" height="24">
            <font face="Times New Roman">D.C. No.:</font>
            <font face="Times New Roman" size="3"> </font>
            <font face="Times New Roman"> <b>' . $inv_data->invoice_prefix_id . '</b></font>
        </td>
        <td width="144" height="24">
            <font face="Times New Roman">Date: <b>' . $vpo_Dt . '</b></font>
        </td>
       </tr>
       <tr>
        <td width="361" height="23" colspan="2">
            <font face="Times New Roman">
                PO No :<b>
                </b></font><b>
                <font face="Times New Roman">
                ' . $inv_data->reference_no . '&nbsp;</font>
            </b>
        </td>
       </tr>
       <tr>
        <td width="361" height="11" align="justify" valign="top" colspan="2">
            <font face="Times New Roman">
                Date :<b> </b></font><b>
                <font face="Times New Roman">
                ' . $Ref_Dt . '</font>
            </b>
        </td>
      </tr>
      <tr>
        <td width="361" colspan="2" height="23">
            <font face=Calibri" size="2">
                <b>' . $inv_data->details . '</b>
            </font>
        </td>

      </tr>';

        return $supplier_info_div;
    }
    private function make_grand_total_div_vpo($inv_data)
    {
        $shi_char = " ₹" . $inv_data->shipping_charges;
        $round_of = " ₹" . $inv_data->round_of;
        $payable_amt = " ₹" . $inv_data->payable_amt;

        $tbl_tax = $this->make_tax_table($inv_data);
        $grand_total_div =
        '  <tr>
        <td width="739" height="49" valign="top" colspan="3">
            <b>
                <font face="Times New Roman">Note : </font>
            </b>
            <font face="Times New Roman" size="3">' . $inv_data->notes . '</font>
        </td>
       </tr>';

        return $grand_total_div;
    }

    // Print invoices for TAX INVOICES.................
    public function TI_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div_TXInvoice($inv_data);
        $grand_total_div = $this->make_grand_total__TXInvoic_div($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/GSTInvoice.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 35;
        $middle_pages_row_count = 40;
        $last_page_row_count = 38;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            //call by function grand tatal row   this  invoices
            //Column tatal (Discount, taxable amt. , net amount )
            $grand_total_row = $this->get_grand_total_TXInvoic_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }
    // call private functiones(make_supplier_info_div_TXInvoice,make_grand_total_div_TXInvoices,get_grand_total_TXInvoic_row).........
    public function make_grand_total__TXInvoic_div($inv_data)
    {
        $shi_char = " ₹" . $inv_data->shipping_charges;
        $round_of = " ₹" . $inv_data->round_of;
        $payable_amt = " ₹" . $inv_data->payable_amt;
        $rupees = $this->displaywords($inv_data->payable_amt);
        $tbl_tax = $this->make_tax_table($inv_data);

        $grand_total_div =
        ' <tr>
        <td width="50" rowspan="2" height="50" valign="top">
            <font face="Calibri" size="2"><b><br>
                    Rupees :</b>' . $rupees . '
            </font>
        </td>
        <td width="300" height="115" rowspan="5" align="justify" style="padding: 0;">
            ' . $tbl_tax . '
        </td>
        <td width="500" height="15" rowspan="2" valign="top" align="right">
            <font face="Calibri">
                <font size="2">Shipping Charges: </font> <b>
                    <font size="2">' . $inv_data->shipping_charges . '</font>
                </b>
            </font>
        </td>
            </tr>
       <tr> </tr>
      <tr>
        <td rowspan="3"></td>
        <td width="378" height="15" rowspan="2" valign="top" align="right">
            <font face="Calibri">
                <font size="2">Round Of : </font> <b>
                    <font size="2">' . $inv_data->round_of . '</font>
                </b>
            </font>
        </td>
       </tr>
       <tr></tr>
       <tr>
        <td width="378" height="17" valign="top" align="right">
            <font face="Calibri">
                <font size="2">Net Total : </font> <b>
                    <font size="2">' . $inv_data->payable_amt . '</font>
                </b>
            </font>
        </td>
      </tr>';

        return $grand_total_div;

    }

    public function make_supplier_info_div_TXInvoice($inv_data)
    {
        $originalDate = $inv_data->date;
        $vpo_Dt = date('d M Y', strtotime($originalDate));

        $originalDate = $inv_data->reference_date;
        $Ref_Dt = date('d M Y', strtotime($originalDate));

        if ($inv_data->branch_adrs_id == $inv_data->sec_branch_adrs_id) {
            $sec_bran_adrs = "";
            $sec_shipp_bran_adrs = "";
            $name = '';
        } else if ($inv_data->branch_adrs_id != $inv_data->sec_branch_adrs_id) {
            $name = 'Shipping Address';
            $sec_bran_adrs = $inv_data->sec_branch_name;
            $sec_shipp_bran_adrs = $inv_data->sec_branch_address;
        }

        $supplier_info_div = ' <tr>
        <td width="378" height="82" rowspan="4" valign="top" align="justify">
            <b>
                <font size="2" face="Calibri">Billing Address :<br>
                    M/s.' . $inv_data->branch_name . '
                    <br>
                </font>
            </b>
            <font size="2" face="Calibri">' . $inv_data->branch_address . '</font><br>
        </td>
        <td width="217" height="24">
            <font face="Calibri" size="2">Invoice No: </font>
            <font face="Calibri"> <b>' . $inv_data->invoice_prefix_id . '</b></font>
        </td>
        <td width="114" height="24">
            <font face="Calibri" size="2">Date: <b>' . $vpo_Dt . '</b></font>
        </td>
     </tr>
      <tr></tr>
      <tr></tr>
       <tr>
                     <td width="167" height="25" align="justify" valign="top">
                <font face="Calibri" size="2">Your Ref :<b>
                        </b></font><b>
                        <font size="2" face="Calibri">' . $inv_data->reference_no . '&nbsp; </font>
                    </b>
            </td>
            <td width="191" height="25" align="justify" valign="top">
                <font size="2" face="Calibri">Date :<b> </b></font><b>
                        <font size="2" face="Calibri">' . $Ref_Dt . '</font>
                    </b>
            </td>
     </tr>
     <tr>
         <td width="378" height="55"  valign="top" align="justify">
          <font face="Calibri" size="2"><b>' . $name . '<br>' . $sec_bran_adrs . '
          </b></br></font>
          <font size="2" face="Calibri">' . $sec_shipp_bran_adrs . '&nbsp; </font>
                     </td>
          <td width="361" colspan="2" height="23">
          <font face="Calibri" size="2">
            <b>' . $inv_data->details . '&nbsp;</b>
           </font>
        </td>
      </tr>
        ';

        return $supplier_info_div;

    }

    private function get_grand_total_TXInvoic_row($inv_data)
    {
        $grand_total_row =
        '<tr>
                <td colspan="4" class="text-end"><b>
                            <font face="Calibri" size="2">Total:</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->total_discount . ' ₹</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->total_taxable_amt . '</font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2"></font>
                        </b></td>
                <td class="text-end"><b>
                            <font face="Calibri" size="2">' . $inv_data->grand_total . '</font>
                        </b></td>
            </tr>';
        return $grand_total_row;
    }

    // paymant receipt print invoices.....................
    public function PR_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        $supplier_info_div = $this->make_supplier_info_div_PR($inv_data);
        $grand_total_div = $this->make_grand_total_div_PR($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/Receipt.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 39;
        $middle_pages_row_count = 44;
        $last_page_row_count = 42;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    //paymant voucher printing invoices...............
    public function PV_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        // This function used for details are same paymant receipt and paymant voucher (supplier adress)
        $supplier_info_div = $this->make_supplier_info_div_PR($inv_data);
        // This function used for details are same paymant receipt and paymant voucher (footer)
        $grand_total_div = $this->make_grand_total_div_PR($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/Voucher.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 39;
        $middle_pages_row_count = 44;
        $last_page_row_count = 42;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    //used this function tow invoices (payment receipt and payment voucher)
    private function make_supplier_info_div_PR($inv_data)
    {
        $originalDate = $inv_data->date;
        $vpo_Dt = date('d M Y', strtotime($originalDate));

        $originalDate = $inv_data->reference_date;
        $Ref_Dt = date('d M Y', strtotime($originalDate));

        $supplier_info_div =
        '<tr>
        <td width="378" height="118" rowspan="2" valign="top" align="justify">
            <b>
                <font face="Times New Roman">From / To :<br>
                    M/s.
                    ' . $inv_data->branch_name . '<br>
                </font>
            </b>
            <font face="Times New Roman">' . $inv_data->branch_address . '</font>
        </td>
        <td width="225" height="24">
            <font face="Times New Roman">No:</font>
            <font face="Times New Roman" size="3"> </font>
            <font face="Times New Roman"> <b>' . $inv_data->invoice_prefix_id . '</b></font>
        </td>
        <td width="136" height="24">
            <font face="Times New Roman">Date: <b>' . $vpo_Dt . '</b></font>
        </td>
      </tr>
       <tr>
        <td width="225" height="11" align="justify" valign="top">
            <font face="Times New Roman">Vouc. Type :<b>
                </b></font><b>
                <font face="Times New Roman">
                    &nbsp; </font>
            </b>
        </td>
        <td width="136" height="11" align="justify" valign="top">
        </td>
      </tr>
            ';

        return $supplier_info_div;
    }
    private function make_grand_total_div_PR($inv_data)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        $shi_char = " ₹" . $inv_data->shipping_charges;
        $round_of = " ₹" . $inv_data->round_of;
        $payable_amt = " ₹" . $inv_data->payable_amt;

        $tbl_tax = $this->make_tax_table($inv_data);
        $grand_total_div =
            '<tr>
            <td width="378" height="43" valign="top">
                <font face="Times New Roman" size="3"><b>Rupees :</b><br> {sRupees}
                </font>
            </td>
            <td width="225" height="43" align="justify">
                <p align="center"><b>Total :</b>
            </td>
            <td width="136" height="43" align="justify">
                <p align="right"><b>{dGrand_Total}&nbsp;&nbsp;&nbsp;&nbsp; </b>
            </td>
        </tr>
        <tr>
            <td width="378" height="28" valign="top" align="center">
                <p align="left"><b>Paid by : </b>{sPay_By}
            </td>
            <td width="361" height="88" colspan="2" rowspan="3">
                <p align="center">
                    <font face="Times New Roman" size="3">For
                        <b>' . $comp_name . '</b>
                    </font>
                </p>
                <p align="center">
                    <font face="Times New Roman" size="3">
                        <br> Authorised Signatory
                    </font>
            </td>
        </tr>
        <tr>
            <td width="378" height="28" valign="top" align="center">
                <p align="left"><b>Payment Details : </b>{sPay_Dt}
            </td>
        </tr>
        <tr>
            <td width="378" height="33" valign="top" align="center">
                <p align="left"><b>Remarks :</b> {sRemarks}
            </td>
        </tr>';

        return $grand_total_div;
    }

    // RCN and RDN notes invoices details .....................
    public function RDN_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        // this function  used for (make_supplier_info_div_RCN,make_grand_total_div_RCN) are same details in(RCN and RDN)
        $supplier_info_div = $this->make_supplier_info_div_RCN($inv_data);
        $grand_total_div = $this->make_grand_total_div_RCN($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/DbNote.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 15;
        $middle_pages_row_count = 18;
        $last_page_row_count = 20;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    public function RCN_inv($inv_id, $print)
    {
        // get company name from session
        $comp_name = $this->session->get('user_details')['comapny_name'];

        // get transactions details or products details
        $inv_data = model('transaction_models/EstModel')->getInvoice($inv_id);
        $inv_details_data = model('transaction_models/EstModel')->getInvoiceDetails($inv_id);

        // Get header and footer  for report
        $header = model('Templates')->get_header_of_report($inv_data->trans_type_id);
        $footer = model('Templates')->get_footer_of_report($inv_data->trans_type_id);
        $header = $header->content;
        $footer = str_replace(['{Comp}'], [$comp_name], $footer->content);

        // make supplier information and net total with tax table  divisions dynamically for showing on printing
        // this function  used for (make_supplier_info_div_RCN,make_grand_total_div_RCN) are same details in(RCN and RDN)
        $supplier_info_div = $this->make_supplier_info_div_RCN($inv_data);
        $grand_total_div = $this->make_grand_total_div_RCN($inv_data);

        // add serial no and do all necessary changes in invoice details data
        $inv_details_data = $this->add_serial_no($inv_details_data);
        $tmplt = file_get_contents(base_url('public/trans_printing_format/CrDbNote.htm'));

        // declare the first, middle and last page product row size
        $first_page_row_count = 15;
        $middle_pages_row_count = 18;
        $last_page_row_count = 20;

        // get count of total products, so that we can check the pages are multiple or not
        $count = count($inv_details_data);

        // check products are capable to render on one page or not (less 7 rows of tax table and grand total row)
        if ($print && $count >= $first_page_row_count - 7) {
            $html = '';
            while ($count > 0) {
                // assign row count on a page
                if ($supplier_info_div) {
                    $row_count = $first_page_row_count;
                } else if ($count <= $last_page_row_count) {
                    $row_count = $last_page_row_count;
                } else {
                    $row_count = $middle_pages_row_count;
                }

                // get products for render on particular page
                $products = array_slice($inv_details_data, 0, $row_count);
                $data['product_info'] = $products;

                // check the products array is last one or not so that we can attach the totals row at the end
                $grand_total_row = '';
                if (count($products) != $row_count && count($products)) {
                    // make totals row structure with value
                    $grand_total_row = $this->get_grand_total_row($inv_data);
                }

                // remove the product that are already rendered
                $inv_details_data = array_slice($inv_details_data, $row_count);

                // make the html part that should be print properly with header and footer on all pages (by parsing or templating)
                $html .= '<div class="content-block">';
                if ($supplier_info_div) {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, $supplier_info_div, $grand_total_row);
                    $supplier_info_div = '';
                } else if ($row_count - $count >= 6) {
                    $html .= $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, '', $grand_total_row);
                    $grand_total_div = '';
                } else {
                    $html .= $this->parse_data($tmplt, $data, $header, '', $footer, '', $grand_total_row);
                }
                $html .= "</div>";

                // update count for loop or break loop if not necessary
                if ($grand_total_div) {
                    // assign 7 in count for a last page for tax table (if the count become 0 but tax table is not render yet)
                    $count = count($products) && $count - count($products) ? $count - count($products) : 7;
                } else {
                    break;
                }
            }
            return $html;
        } else {
            $data["product_info"] = $inv_details_data;
            $grand_total_row = $this->get_grand_total_row($inv_data);
            return $this->parse_data($tmplt, $data, $header, $grand_total_div, $footer, $supplier_info_div, $grand_total_row);
        }
    }

    //used this function tow invoices (RCN  Invoices  and RDN Invoices)
    public function make_grand_total_div_RCN($inv_data)
    {
        $comp_name = $this->session->get('user_details')['comapny_name'];

        $shi_char = " ₹" . $inv_data->shipping_charges;
        $round_of = " ₹" . $inv_data->round_of;
        $payable_amt = " ₹" . $inv_data->payable_amt;
        $rupees = $this->displaywords($inv_data->payable_amt);
        $tbl_tax = $this->make_tax_table($inv_data);
        $grand_total_div =
        '<tr>
            <td width="378" height="64" valign="top">
                <font face="Times New Roman" size="3"><b>Rupees :</b><br> ' . $rupees . '                </font>
            </td>
            <td width="225" height="64" align="justify">
                <p align="center"><b>Total :</b>
            </td>
            <td width="136" height="64" align="justify">
                <p align="right"><b>' . $inv_data->payable_amt . '&nbsp;&nbsp;&nbsp;&nbsp; </b>
            </td>
           </tr>';

        return $grand_total_div;
    }

    private function make_supplier_info_div_RCN($inv_data)
    {
        $originalDate = $inv_data->date;
        $vpo_Dt = date('d M Y', strtotime($originalDate));

        $originalDate = $inv_data->reference_date;
        $Ref_Dt = date('d M Y', strtotime($originalDate));

        $supplier_info_div =
        ' <tr>
            <td width="378" height="118" rowspan="3" valign="top" align="justify">
                <b>
                    <font face="Times New Roman">To :<br>
                        M/s.
                        ' . $inv_data->branch_name . '<br>
                    </font>
                </b>
                <font face="Times New Roman">' . $inv_data->branch_address . '</font>
            </td>
            <td width="225" height="24">
                <font face="Times New Roman">No:</font>
                <font face="Times New Roman" size="3"> </font>
                <font face="Times New Roman"> <b>' . $inv_data->invoice_prefix_id . '</b></font>
            </td>
            <td width="136" height="24">
                <font face="Times New Roman">Date: <b>' . $vpo_Dt . '</b></font>
            </td>
        </tr>
        <tr>
            <td width="225" height="11" align="justify" valign="top">
                <font face="Times New Roman">
                    Your Ref. No :<b>
                    </b></font><b>
                    <font face="Times New Roman">
                    ' . $inv_data->reference_no . '&nbsp; </font>
                </b>
            </td>
            <td width="136" height="11" align="justify" valign="top">
                <font face="Times New Roman">
                    Date :<b> </b></font><b>
                    <font face="Times New Roman">
                   ' . $Ref_Dt . '</font>
                </b>
            </td>
        </tr>
        <tr>
            <td width="225" height="25" align="justify" valign="top">
                <font face="Times New Roman">Our Ref. No. :<b> </b></font><b>
                    <font face="Times New Roman">
                        {nRef_No1}</font>
                </b>
            </td>
            <td width="136" height="25" align="justify" valign="top">
                <font face="Times New Roman">Date: <b>{dtRef_Dt}</b></font>
            </td>
        </tr>';

        return $supplier_info_div;
    }
    //Rupees changes ..................
    public function displaywords($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
        "and " . $words[(floor($point / 10)) * 10] . " " .
        $words[$point = $point % 10] : '';
        $result = $result . "rupees " . ($points ? $points . " paise" : "");
        return ucwords($result);
    }

    // all invoices are used for dufault foote.............
    public function insert_default_footer($branch_id)
    {
        $info = model('Branches')->get_branch_info($branch_id);
        $data = [
            'Comp_name' => $info->company,
        ];
        $tmplt = file_get_contents(base_url('public/trans_printing_format/footer.htm'));
        $footer = $parse = $this->parser->setData($data)->renderString($tmplt);

        $trans_types = model('TransactionModel')->get_transaction_type_list();
        $insert_data = [];
        foreach ($trans_types as $type) {
            $insert_data[] = [
                'branch_id' => $branch_id,
                'trans_type_id' => $type->id,
                'sub_type_id' => 3,
                'title' => $type->title . ' Footer',
                'content' => $footer,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ];
        }
        $res = model('Templates')->insert_batch($insert_data);
    }

}