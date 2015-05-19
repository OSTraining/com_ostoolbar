<?php
defined('_JEXEC') or die;

class OSToolbarModelTutorial extends OstoolbarModel
{
    protected function populateState()
    {
        $id = JRequest::getVar('cid', array(), 'request', 'array');
        if (empty($id)) :
            $id = JRequest::getInt('id', 0);
        else :
            $id = $id[0];
        endif;

        $this->setState('id', $id);
    }

    public function getData()
    {
        $id         = $this->getState('id', 0);
        $this->data = OstoolbarCache::callback($this, '_fetchData', array($id));
        return $this->data;
    }

    public function _fetchData($id)
    {
        $data = array('resource' => 'article', 'id' => $id);

        $response = OstoolbarRequest::makeRequest($data);
        if ($response->hasError()) :
            JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
            //$this->setError('Error:  '.$response->getErrorMsg().' (Code '.$response->getErrorCode().')');
            return false;
        endif;

        $body            = $response->getBody();
        $body->introtext = OstoolbarRequest::filter($body->introtext);
        $body->fulltext  = OstoolbarRequest::filter($body->fulltext);

        return $body;
    }
}
