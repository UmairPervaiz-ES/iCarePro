<?php

namespace App\Http\Controllers;
use Spatie\Analytics\Period;
use Illuminate\Routing\Controller as BaseController;
  
class UtilityController extends BaseController
{
    
  public function getGoogleAnalytics()
  {
    $analyticsData = \Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
    $data_array = array();
    foreach ($analyticsData as $record) {
      $data['date'] = $record['date']->toDateString();
      $data['visitors'] = $record['visitors'];
      $data['pageViews'] = $record['pageViews'];
      $data_array['visitors'][] = $data;
    }
    $analyticsRefers = \Analytics::fetchTopReferrers(Period::days(7));
    foreach ($analyticsRefers as $refer) {
      $data1['referUrl'] = $refer['url'];
      $data1['pageViews'] = $refer['pageViews'];
      $data_array['refers'][] = $data1;
    }

    $analyticsUsers = \Analytics::fetchUserTypes(Period::days(7));
    foreach ($analyticsUsers as $user) {
      $data2['userType'] = $user['type'];
      $data2['userSessions'] = $user['sessions'];
      $data_array['usersType'][] = $data2;
    }
    $analyticsVisited = \Analytics::fetchMostVisitedPages(Period::days(7));
    foreach ($analyticsVisited as $page) {
      $data3['url'] = $page['url'];
      $data3['pageTitle'] = $page['pageTitle'];
      $data3['pageViews'] = $page['pageViews'];
      $data_array['visitedPages'][] = $data3;
    }
    return $data_array;
  }

}