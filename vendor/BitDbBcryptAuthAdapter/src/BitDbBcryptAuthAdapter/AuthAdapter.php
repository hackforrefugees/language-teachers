<?php

/**
 * Dejan Cabrilo <dcabrilo@bitspan.rs>
 *
 * @link      https://github.com/cabrilo/BitDbBcryptAuthAdapter for the canonical source repository
 * @copyright Dejan Cabrilo
 * @license   https://github.com/cabrilo/BitDbBcryptAuthAdapter/blob/master/LICENSE New BSD License
 */

namespace BitDbBcryptAuthAdapter;

use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Sql\Predicate\Operator;

/**
 * Class AuthAdapter
 * @package BitDbBcryptAuthAdapter
 */
class AuthAdapter extends DbTable\AbstractAdapter
{
    /**
     * Get the stored password for supplied identity
     *
     * @return \Zend\Db\Sql\Select
     */
    public function authenticateCreateSelect()
    {
        $dbSelect = clone $this->getDbSelect();
        $dbSelect->from($this->tableName)
            ->columns(array($this->credentialColumn))
            ->where(new Operator($this->identityColumn, '=', $this->identity));

        return $dbSelect;
    }

    /**
     * Check if the password is Bcrypt verified.
     *
     * @param array $resultIdentity
     *
     * @return Result
     */
    public function authenticateValidateResult($resultIdentity)
    {
        if (!$resultIdentity || !isset($resultIdentity['password'])) {
            $this->authenticateResultInfo['code'] = Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticateResultInfo['messages'][] = 'Supplied identity does not exist.';
            return $this->authenticateCreateAuthResult();
        }

        $bcrypt = new Bcrypt();
        if (!$bcrypt->verify($this->credential, $resultIdentity['password'])) {
            $this->authenticateResultInfo['code'] = Result::FAILURE_CREDENTIAL_INVALID;
            $this->authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
            return $this->authenticateCreateAuthResult();
        }

        $this->resultRow = $resultIdentity;

        $this->authenticateResultInfo['code'] = Result::SUCCESS;
        $this->authenticateResultInfo['messages'][] = 'Authentication successful.';
        return $this->authenticateCreateAuthResult();
    }
}