<?php namespace App\Tz\Aidstream\Repositories\DocumentLink;

use App\Tz\Aidstream\Models\DocumentLink;

/**
 * Class DocumentLinkRepository
 * @package App\Tz\Aidstream\Repositories\DocumentLink
 */
class DocumentLinkRepository implements DocumentLinkRepositoryInterface
{

    protected $documentLink;

    public function __construct(DocumentLink $documentLink)
    {
        $this->documentLink = $documentLink;
    }

    /**
     * Create document link
     * @param $documentLinks
     * @return bool
     */
    public function create($documentLinks)
    {
        foreach ($documentLinks as $documentLink) {
            $newDocumentLink = $this->documentLink->newInstance($documentLink);
            $newDocumentLink->save();
        }

        return true;
    }

    /**
     * Find document link by project id
     * @param $projectId
     * @return mixed
     */
    public function findByProjectId($projectId)
    {
        return $this->documentLink->where('activity_id', '=', $projectId)->get();
    }

    /**
     * Update Document Link
     * @param $projectId
     * @param $request
     * @return bool
     */
    public function update($projectId, $request)
    {
        foreach ($request['document_link'] as $document) {
            if (array_key_exists('id', $document) && boolval($document['id'])) {
                $documentLink = $this->documentLink->find($document['id']);
                unset($document['id']);
                $documentLinkData = [
                    'activity_id'   => $projectId,
                    'document_link' => $document
                ];
                $documentLink->update($documentLinkData);
            } else {
                if ((getVal($document, ['title', 0, 'narrative', 0, 'narrative']) || getVal($document, ['url']))) {
                    unset($document['id']);
                    $documentLinkData = [
                        'activity_id'   => $projectId,
                        'document_link' => $document
                    ];
                    $newDocument = $this->documentLink->newInstance($documentLinkData);
                    $newDocument->save();
                }
            }
        }

        return true;
    }
}
