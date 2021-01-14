<?php namespace Atomino\Molecules\Module\Attachment;
/**
 * @property-read int|null $id
 * @property array $attachments
 * @method \Atomino\Molecules\Module\Attachment\AttachmentCollectionInterface|null getAttachmentCollection(string $name)
 */
interface AttachmentableInterface{
	const EVENT_ATTACHMENT_ADDED = 'EVENT_ATTACHMENT_ADDED';
}