<?php

namespace ceLTIc\LTI\MediaType;

use ceLTIc\LTI\Tool;
use ceLTIc\LTI\Profile\ServiceDefinition;

/**
 * Class to represent an LTI Tool Proxy media type
 *
 * @author  Waqar Muneer <waqarmuneer@gmail.com>
 * @copyright  SPV Software Products
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3
 */
class ToolProxy
{

    /**
     * Class constructor.
     *
     * @param Tool              $tool   Tool  object
     * @param ServiceDefinition $toolProxyService  Tool Proxy service
     * @param string            $secret  Shared secret
     */
    function __construct($tool, $toolProxyService, $secret)
    {
        $contexts = array();

        $this->{'@context'} = array_merge(array('http://purl.imsglobal.org/ctx/lti/v2/ToolProxy'), $contexts);
        $this->{'@type'} = 'ToolProxy';
        $this->{'@id'} = "{$toolProxyService->endpoint}";
        $this->lti_version = 'LTI-2p0';
        $this->tool_consumer_profile = $tool->platform->profile->{'@id'};
        $this->tool_profile = new ToolProfile($tool);
        $this->security_contract = new SecurityContract($tool, $secret);
    }

}
