<?php

class modDeveloperAnalyticsHelper {

    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */
    public static function getChart() {
        $document = JFactory::getDocument();

        $visits = self::getVisitCounts();
        $total = $visits->tome;
        $onem = $visits->tome_1m;
        $tfdays = $visits->tome_24d;
        $etdays = $visits->tome_18d;
        $tvdays = $visits->tome_12d;
        $sdays = $visits->to_me_6d;

        $totalt = $visits->byme;
        $onemt = $visits->byme_1m;
        $tfdayst = $visits->byme_24d;
        $etdayst = $visits->byme_18d;
        $tvdayst = $visits->byme_12d;
        $sdayst = $visits->byme_6d;

        $totala = $visits->tomyapps;
        $onema = $visits->tomyapps_1m;
        $tfdaysa = $visits->tomyapps_24d;
        $etdaysa = $visits->tomyapps_18d;
        $tvdaysa = $visits->tomyapps_12d;
        $sdaysa = $visits->tomyapps_6d;



        $jsArrTome = "[" . $onem . "," . $tfdays . "," . $etdays . "," . $tvdays . "," . $sdays . "]";
        $jsArrToThem = "[" . $onemt . "," . $tfdayst . "," . $etdayst . "," . $tvdayst . "," . $sdayst . "]";
        $jsArrToApps = "[" . $onema . "," . $tfdaysa . "," . $etdaysa . "," . $tvdaysa . "," . $sdaysa . "]";


        echo'<div style="display:none;" id="container-profileviews" style="min-width: 310px; height: 400px; margin: 0 auto"></div><div style="display:none;" id="container-profileviewsm" style="min-width: 310px; height: 400px; margin: 0 auto"></div><div style="display:none;" id="container-profileviewap" style="min-width: 310px; height: 400px; margin: 0 auto"></div> ';
        $document->addScriptDeclaration("
    jQuery(function () {
    jQuery('#container-profileviews').highcharts({
	chart: {
            borderColor: '#EBBA95',
            borderRadius: 20,
            borderWidth: 2,
            type: 'area'
        },
    title: {
    text: '" . $total . " views in the last 30 days',
    x: -20 ,
	style: {
          fontWeight: 'bold'
            }
    },
    xAxis: {
    categories: ['a month', '24 days', '18 days', '12 days', '6 days']
    },
    yAxis: {
    title: {
    text: 'Profile views'
    },
    plotLines: [{
    value: 0,
    width: 1,
    color: '#808080'
    }]
    },
	plotOptions: {
    line: {
        dataLabels: {
            enabled: true
        }

    }
    },
    tooltip: {
	backgroundColor: '#FCFFC5',
    borderColor: '#559928',
    borderRadius: 10,
    borderWidth: 3,
    valueSuffix: ' views'
    },
	credits:{
	enabled:false
	},
    legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle',
	backgroundColor: '#FCFFC5',
    borderWidth: 2,
	borderColor:'#3C880A'
    },
    series: [{
    name: 'Profile views',
    data: " . $jsArrTome . "
        }]
    });
	
	jQuery('#visits_to_me').on('click',function(){jQuery('#container-profileviews').bPopup()});
	
	    jQuery('#container-profileviewsm').highcharts({
	chart: {
            borderColor: '#EBBA95',
            borderRadius: 20,
            borderWidth: 2,
            type: 'spline'
        },
    title: {
    text: '" . $totalt . " views in the last 30 days',
    x: -20 ,
	style: {
          fontWeight: 'bold'
            }
    },
    xAxis: {
    categories: ['a month', '24 days', '18 days', '12 days', '6 days']
    },
    yAxis: {
    title: {
    text: 'Profile views'
    },
    plotLines: [{
    value: 0,
    width: 1,
    color: '#808080'
    }]
    },
	plotOptions: {
    line: {
        dataLabels: {
            enabled: true
        }

    }
    },
    tooltip: {
	backgroundColor: '#FCFFC5',
    borderColor: '#559928',
    borderRadius: 10,
    borderWidth: 3,
    valueSuffix: ' views'
    },
	credits:{
	enabled:false
	},
    legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle',
	backgroundColor: '#FCFFC5',
    borderWidth: 2,
	borderColor:'#3C880A'
    },
    series: [{
    name: 'Profile views',
    data: " . $jsArrToThem . "
        }]
    });
	
	jQuery('#visits_by_me').on('click',function(){jQuery('#container-profileviewsm').bPopup()});
	
	jQuery('#container-profileviewap').highcharts({
	chart: {
            borderColor: '#EBBA95',
            borderRadius: 20,
            borderWidth: 2,
            type: 'area'
        },
    title: {
    text: '" . $totala . " views in the last 30 days',
    x: -20 ,
	style: {
          fontWeight: 'bold'
            }
    },
    xAxis: {
    categories: ['a month', '24 days', '18 days', '12 days', '6 days']
    },
    yAxis: {
    title: {
    text: 'Profile views'
    },
    plotLines: [{
    value: 0,
    width: 1,
    color: '#808080'
    }]
    },
	plotOptions: {
    line: {
        dataLabels: {
            enabled: true
        }

    }
    },
    tooltip: {
	backgroundColor: '#FCFFC5',
    borderColor: '#559928',
    borderRadius: 10,
    borderWidth: 3,
    valueSuffix: ' views'
    },
	credits:{
	enabled:false
	},
    legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle',
	backgroundColor: '#FCFFC5',
    borderWidth: 2,
	borderColor:'#3C880A'
    },
    series: [{
    name: 'Profile views',
    data: " . $jsArrToApps . "
        }]
    });
	
	jQuery('#visits_to_my_apps').on('click',function(){jQuery('#container-profileviewap').bPopup()});
	
})");
    }

    private static function getVisitCounts() {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_visitcounts WHERE uid='" . $user->id . "'";
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

}

?>