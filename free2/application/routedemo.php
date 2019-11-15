<?php

namespace free2\application;

use free2\registrationDemo\coreExample;
use pseph\nff\framework\SecuityBase;
use pseph\nff\framework\SecurityServices;

class routedemo extends SecuityBase
{
    public function getsecuitylevelfor($arrConfiguration)
    {
        return SecurityServices::NOTLOGGEDIN;
    }


    public function execute($arrConfiguration)
    {
        switch ($arrConfiguration[2]) {
            case "demo":

                coreExample::demoEntryPoint();

                break;
        }
    }

}