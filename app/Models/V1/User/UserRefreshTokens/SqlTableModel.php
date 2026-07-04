<?php

namespace App\Models\V1\User\UserRefreshTokens;

use App\Models\V1\BaseTableModel;

class SqlTableModel extends BaseTableModel
{
    protected $DBGroup        = DB_GROUP_001;
    protected $table          = 'user_refresh_tokens';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = true;

    protected $allowedFields = [
        'user_id',
        'token_hash',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent',
    ];

    protected array $sortableFields = ['created_at', 'expires_at'];
    protected array $likeFields     = [];
    protected $hidden         = ['token_hash'];

    public function findActiveByTokenHash(string $hash): ?array
    {
        return $this->where('token_hash', $hash)
                    ->where('used_at', null)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->first();
    }

    public function markAsUsed(int $id): void
    {
        $this->update($id, ['used_at' => date('Y-m-d H:i:s')]);
    }

    public function revokeByUserId(int $userId): void
    {
        $this->where('user_id', $userId)
             ->where('used_at', null)
             ->set(['used_at' => date('Y-m-d H:i:s')])
             ->update();
    }
}
