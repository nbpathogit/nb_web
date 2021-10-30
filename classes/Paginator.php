<?php

/**
* Paginator
*
* Data for selecting a page of records
*/
class Paginator
{
    /**
    * Number of records to retrun
    * @var integer
    */
    public $limit;
    
    /**
    * Number of records to skip before the page
    * @var integer
    */
    public $offset;

    /**
    * Previous page number
    * @var integer
    */
    public $previous;

    /**
    * Next page number
    * @var integer
    */
    public $next;

    /**
    * Constructor
    *
    *@param integer $page Page number
    *@param integer $records_per_page Number of records per page
    *
    *@return void
    */
    public function __construct($page, $records_per_page,$total_records){
        $this->limit = $records_per_page;
        // filter_ver not support in PHP8
        // $page=filter_ver($page, FILTER_VALIDATE_INT,
        //     ['options' => [
        //         'default'=> 1
        //         ]
        //     ]
        // );

        if($page > 1){
            $this->previous = $page -1;
        }

        $total_page = ceil($total_records / $records_per_page); // ceil is round up
        
        if($page< $total_page){
            $this->next= $page + 1;
        }

        $this->offset = $records_per_page * ($page-1);

    }
}