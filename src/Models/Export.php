<?php

/** ------------------------------
 ** Get all db values
 * -------------------------------
 ** Call class 
 * -------------------------------
 *TODO echo results to browser
 *
 * $entries = $db->get($table[0]);
 *
 * $export = new Export($entries, "filename");
 *
 * echo $export->csv();
 */

class Export
{

    protected $form;
    protected $fileName;

    function __construct(array $form, $fileName)
    {
        $this->form = $form;
        $this->fileName = $fileName;
    }

    private function array2csv(array &$results)
    {
        if (count($results) == 0) {
            return null;
        }
        ob_start();
        $data = fopen("php://output", 'w');
        fputcsv($data, array_keys(reset($results)));
        foreach ($results as $row) {
            fputcsv($data, $row);
        }
        fclose($data);
        return ob_get_clean();
    }

    /**
     * Export CSV file
     * @param {array} form all content to be exported
     * @param {string} fileName export file name
     */
    public function csv()
    {
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . basename($this->fileName) . '.csv');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        // Export file to download
        return self::array2csv($this->form);
    }
}
