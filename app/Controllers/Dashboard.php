<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM trusmi_tes.table_kpi_marketing");
        $data['kpi_marketing'] = $query->getResultArray();

        // $query2 = $db->query("SELECT * FROM trusmi_tes.table_kpi_marketing");
        // $data['rule_story'] = $query2->getResultArray();

        $query2 = $db->query("SELECT a.karyawan,
        COALESCE(b.jumlah_sales, 0) as jumlah_sales,
        COALESCE(c.jumlah_report, 0) as jumlah_report,
        COALESCE(d.jumlah_aktual_sales, 0) as jumlah_aktual_sales,
        COALESCE(e.jumlah_aktual_report, 0) as jumlah_aktual_report,
        ROUND(COALESCE(d.jumlah_aktual_sales, 0) / COALESCE(b.jumlah_sales, 1) * 100) as pencapaian_sales_pct,
       ROUND(COALESCE(e.jumlah_aktual_report, 0) / COALESCE(c.jumlah_report, 1) * 100) as pencapaian_report_pct,
        ROUND(COALESCE(d.jumlah_aktual_sales, 0) / COALESCE(b.jumlah_sales, 1) * 50, 0) as actual_bobot_sales,
        ROUND(COALESCE(e.jumlah_aktual_report, 0) / COALESCE(c.jumlah_report, 1) * 50, 0) as actual_bobot_report,
        ROUND((COALESCE(d.jumlah_aktual_sales, 0) / COALESCE(b.jumlah_sales, 1) * 50) + (COALESCE(e.jumlah_aktual_report, 0) / COALESCE(c.jumlah_report, 1) * 50), 0) as kpi_persentase
 FROM
   (SELECT karyawan
    FROM trusmi_tes.table_kpi_marketing
    WHERE karyawan IN ('adi', 'budi')
    GROUP BY karyawan) a
 LEFT JOIN
   (SELECT karyawan, COUNT(*) as jumlah_sales
    FROM trusmi_tes.table_kpi_marketing
    WHERE karyawan IN ('adi', 'budi') AND kpi = 'sales'
    GROUP BY karyawan) b ON a.karyawan = b.karyawan
 LEFT JOIN
   (SELECT karyawan, COUNT(*) as jumlah_report
    FROM trusmi_tes.table_kpi_marketing
    WHERE karyawan IN ('adi', 'budi') AND kpi = 'report'
    GROUP BY karyawan) c ON a.karyawan = c.karyawan
 LEFT JOIN
   (SELECT karyawan,
           SUM(CASE
               WHEN kpi = 'sales' AND aktual <= deadline THEN 1
               ELSE 0
           END) as jumlah_aktual_sales
    FROM trusmi_tes.table_kpi_marketing
    WHERE karyawan IN ('adi', 'budi')
    GROUP BY karyawan) d ON a.karyawan = d.karyawan
 LEFT JOIN
   (SELECT karyawan,
           SUM(CASE
               WHEN kpi = 'report' AND aktual <= deadline THEN 1
               ELSE 0
           END) as jumlah_aktual_report
    FROM trusmi_tes.table_kpi_marketing
    WHERE karyawan IN ('adi', 'budi')
    GROUP BY karyawan) e ON a.karyawan = e.karyawan");
        $data['rule_story'] = $query2->getResultArray();


        $query3 = $db->query("SELECT 
        kpi,
        COUNT(CASE WHEN aktual <= deadline THEN 1 END) AS jumlah_on_time,
        COUNT(CASE WHEN aktual > deadline THEN 1 END) AS jumlah_late,
        ROUND(COUNT(CASE WHEN aktual <= deadline THEN 1 END) / COUNT(*) * 100, 2) AS persentase_on_time,
        ROUND(COUNT(CASE WHEN aktual > deadline THEN 1 END) / COUNT(*) * 100, 2) AS persentase_late
    FROM
        trusmi_tes.table_kpi_marketing
    #WHERE
    #karyawan IN ('adi', 'budi')
    GROUP BY
        kpi;");
        $data['tasklist'] = $query3->getResultArray();

        echo view('dashboard', $data);
    }
}
