<?php

namespace DraperStudio\Friendable\Traits;

use DraperStudio\Friendable\Models\Friend;
use DraperStudio\Friendable\Status;
use Illuminate\Database\Eloquent\Model;

trait Friendable
{
    public function friends()
    {
        return $this->morphMany(Friend::class, 'sender');
    }

    public function befriend(Model $recipient)
    {
        if ($this->isFriendsWith($recipient)) {
            return;
        }

        $friendship = (new Friend())->fill([
            'recipient_id' => $recipient->id,
            'recipient_type' => get_class($recipient),
            'status' => Status::PENDING,
        ]);

        $this->friends()->save($friendship);

        return $friendship;
    }

    public function unfriend(Model $recipient)
    {
        if (!$this->isFriendsWith($recipient)) {
            return;
        }

        return $this->findFriendship($recipient)->delete();
    }

    public function isFriendsWith(Model $recipient, $status = null)
    {
        $exists = $this->findFriendship($recipient);

        if (!empty($status)) {
            $exists = $exists->where('status', $status);
        }

        return $exists->count();
    }

    public function acceptFriendRequest(Model $recipient)
    {
        if (!$this->isFriendsWith($recipient)) {
            return;
        }

        return $this->findFriendship($recipient)->update([
            'status' => Status::ACCEPTED,
        ]);
    }

    public function denyFriendRequest(Model $recipient)
    {
        if (!$this->isFriendsWith($recipient)) {
            return;
        }

        return $this->findFriendship($recipient)->update([
            'status' => Status::DENIED,
        ]);
    }

    public function blockFriendRequest(Model $recipient)
    {
        if (!$this->isFriendsWith($recipient)) {
            return;
        }

        return $this->findFriendship($recipient)->update([
            'status' => Status::BLOCKED,
        ]);
    }

    public function unblockFriendRequest(Model $recipient)
    {
        if (!$this->isFriendsWith($recipient)) {
            return;
        }

        return $this->findFriendship($recipient)->update([
            'status' => Status::PENDING,
        ]);
    }

    public function getFriendship($recipient)
    {
        return $this->findFriendship($recipient)->first();
    }

    public function getAllFriendships($limit = null, $offset = null)
    {
        return $this->findFriendshipsByStatus(null, $limit, $offset);
    }

    public function getPendingFriendships($limit = null, $offset = 0)
    {
        return $this->findFriendshipsByStatus(Status::PENDING, $limit, $offset);
    }

    public function getAcceptedFriendships($limit = null, $offset = 0)
    {
        return $this->findFriendshipsByStatus(Status::ACCEPTED, $limit, $offset);
    }

    public function getDeniedFriendships($limit = null, $offset = 0)
    {
        return $this->findFriendshipsByStatus(Status::DENIED, $limit, $offset);
    }

    public function getBlockedFriendships($limit = null, $offset = 0)
    {
        return $this->findFriendshipsByStatus(Status::BLOCKED, $limit, $offset);
    }

    public function hasBlocked(Model $recipient)
    {
        return $this->getFriendship($recipient)->status === Status::BLOCKED;
    }

    public function isBlockedBy(Model $recipient)
    {
        $friendship = Friend::where(function ($query) use ($recipient) {
            $query->where('sender_id', $this->id);
            $query->where('sender_type', get_class($this));

            $query->where('recipient_id', $recipient->id);
            $query->where('recipient_type', get_class($recipient));
        })->first();

        return $friendship ? ($friendship->status === Status::BLOCKED) : false;
    }

    public function getFriendRequests()
    {
        return Friend::where(function ($query) {
            $query->where('recipient_id', $this->id);
            $query->where('recipient_type', get_class($this));
            $query->where('status', Status::PENDING);
        })->get();
    }

    private function findFriendship(Model $recipient)
    {
        return Friend::where(function ($query) use ($recipient) {
            $query->where('sender_id', $this->id);
            $query->where('sender_type', get_class($this));

            $query->where('recipient_id', $recipient->id);
            $query->where('recipient_type', get_class($recipient));
        })->orWhere(function ($query) use ($recipient) {
            $query->where('sender_id', $recipient->id);
            $query->where('sender_type', get_class($recipient));

            $query->where('recipient_id', $this->id);
            $query->where('recipient_type', get_class($this));
        });
    }

    private function findFriendshipsByStatus($status, $limit, $offset)
    {
        $friendships = [];

        $query = Friend::where(function ($query) use ($status) {
            $query->where('sender_id', $this->id);
            $query->where('sender_type', get_class($this));

            if (!empty($status)) {
                $query->where('status', $status);
            }
        })->orWhere(function ($query) use ($status) {
            $query->where('recipient_id', $this->id);
            $query->where('recipient_type', get_class($this));

            if (!empty($status)) {
                $query->where('status', $status);
            }
        });

        if (!empty($limit)) {
            $query->take($limit);
        }

        if (!empty($offset)) {
            $query->skip($offset);
        }

        foreach ($query->get() as $friendship) {
            $friendships[] = $this->getFriendship($this->find(
                ($friendship->sender_id == $this->id)  ? $friendship->recipient_id : $friendship->sender_id
            ));
        }

        return $friendships;
    }
}
