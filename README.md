# whmcs-mikrotik-ppp

ini adalah module server mikrotik untuk auto provision secret ppp / akun ppp di whmcs.

untuk persyaratannya adalah mikrotik anda harus ada di RouterOS versi 7. disarankan versi stable terakhir karna sudah ada fitur https rest api

untuk penggunaannya bisa download zip repo ini. lalu masukan ke folder /{whmcs-root-path}/{extract-disini}

beberapa hal yang dapat di customize adalah paket paket / profile yang tersedia di router anda masing masing
return array(
        'Profile' => array(
            'Type' => 'dropdown',
            'Options' => array(
                '5mbps' => '5mbps',
                '10mbps' => '10mbps',
                '20mbps' => '20mbps',
                '30mbps' => '30mbps',
                '50mbps' => '50mbps',
                '100mbps' => '100mbps',
				'add more here' => 'add more here'

bagian ini dapat di edit sesuai kebutuhan.










mengapa banyak cikipeh?, watch this https://www.youtube.com/watch?v=CKzcPTO8ifA
