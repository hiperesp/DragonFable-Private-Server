<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\interfaces\Purchasable;
use hiperesp\server\vo\SettingsVO;

class QuestMergeModel extends Model {

    const COLLECTION = 'quest_merge';

    public function getById(int $mergeId): array {
        $questMerge = $this->storage->select(self::COLLECTION, ['id' => $mergeId]);
        if (!empty($questMerge)) {
			return $questMerge[0];
		}
        throw new DFException(DFException::MERGE_NOT_FOUND);
    }

}