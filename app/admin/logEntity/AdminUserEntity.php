<?php
/*
 * @Author: lpc
 * @DateTime: 2022/1/21 15:09
 * @Notes: 管理员信息变更日志数据
 * 
 * @return 

 */

namespace app\admin\logEntity;

use JsonSerializable;

class AdminUserEntity extends BaseLogEntity implements JsonSerializable
{
    protected array $fieldNameMap = [
        'loginName' => '管理员名',
        'avatar' => '头像',
        'role' => '角色',
    ];
    /**
     * 管理员名
     * @var string
     */
    private string $loginName = '';
    /**
     * 管理员头像
     * @var string
     */
    private string $avatar = '';
    /**
     * 管理员角色名
     * @var string
     */
    private string $role = '';

    /**
     * @return string
     */
    public function getLoginName(): string
    {
        return $this->loginName;
    }

    /**
     * @param string $loginName
     */
    public function setLoginName(string $loginName): void
    {
        $this->loginName = $loginName;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $val) {
            if ($val !== null) {
                $data[$key] = $val;
            }
        }
        return $data;
    }
}