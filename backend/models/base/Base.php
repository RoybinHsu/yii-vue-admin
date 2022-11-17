<?php

namespace app\models\base;

class Base extends \yii\db\ActiveRecord
{

    protected bool $timeStamp = false; // 更新日期是否为时间戳

    /**
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if ($insert) {
            if ($this->hasAttribute('created_at')) {
                if ($this->timeStamp) {
                    $this->setAttribute('created_at', time());
                    $this->setAttribute('updated_at', time());
                } else {
                    $this->setAttribute('created_at', date('Y-m-d H:i:s'));
                    $this->setAttribute('updated_at', date('Y-m-d H:i:s'));
                }
            }
        } else {
            if ($this->hasAttribute('created_at')) {
                if ($this->timeStamp) {
                    $this->setAttribute('updated_at', time());
                } else {
                    $this->setAttribute('updated_at', date('Y-m-d H:i:s'));
                }
            }
        }
        return parent::beforeSave($insert);
    }
}
