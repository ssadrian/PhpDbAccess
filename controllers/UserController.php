<?php

require_once "../database/db.php";
require_once "BaseController.php";
require_once "../models/Item.php";
require_once "../models/User.php";
require_once "../utils/helpers.php";

class UserController extends BaseController
{
    public static function getInstance(): UserController
    {
        if (empty($instance)) {
            $instance = new UserController();
        }

        return $instance;
    }

    function getByGuid(string $guid): ?User
    {
        $qry = "SELECT * FROM users WHERE guid = ?";

        $stmt = createPreparedStatement($qry);
        $stmt->bind_param("s", $guid);

        $result = $stmt->get_result();

        if (!$result) {
            return null;
        }

        $result = $result[0];
        return new User(
            $result->name,
            $result->surname,
            $result->username,
            $result->email,
            $result->password,
            $result->guid
        );
    }

    function tryCreate(mixed $item): bool
    {
        if ($item instanceof User === false) {
            return false;
        } elseif (isPasswordStrong($item->password) === false) {
            return false;
        }

        $user = getPurifiedUser(getCompleteUser($item));
        $qry = "INSERT INTO users (guid, name, surname, username, email, password) VALUE (?, ?, ?, ?, ?, ?)";

        $stmt = createPreparedStatement($qry);

        return $stmt->execute([
            $user->guid, $user->name, $user->surname, $user->username, $user->email, $user->password
        ]);
    }

    function tryAddItemToCart(string $itemGuid, string $userGuid): bool
    {
        if (empty($itemGuid) || empty($userGuid)) {
            return false;
        }

        $qry = "INSERT INTO shopping_carts (user, item) VALUE (?, ?)";
        return createPreparedStatement($qry)
            ->execute([$userGuid, $itemGuid]);
    }

    function tryRemoveItemFromCart(string $itemGuid, string $userGuid): bool
    {
        if (empty($itemGuid) || empty($userGuid)) {
            return false;
        }

        $qry = "DELETE FROM shopping_carts WHERE user = ? AND item = ? LIMIT 1";
        return createPreparedStatement($qry)
            ->execute([$userGuid, $itemGuid]);
    }

    function getCartItems(string $userGuid): array
    {
        if (empty($userGuid)) {
            return array();
        }

        $qry = "SELECT item FROM shopping_carts WHERE user = ?";
        $stmt = createPreparedStatement($qry);

        if ($stmt->execute([$userGuid]) === false) {
            return array();
        }

        $results = $stmt->get_result();

        if (!$results) {
            return array();
        }

        $cartItemGuids = array();
        foreach ($results->fetch_all(MYSQLI_ASSOC) as $result) {
            $cartItemGuids[] = $result["item"];
        }

        $cartItems = array();
        foreach ($cartItemGuids as $cartItemGuid) {
            $item = ItemController::getInstance()->getByGuid($cartItemGuid);

            if ($item == null) {
                continue;
            }

            $cartItems[] = $item;
        }

        return $cartItems;
    }

    function getFiltered(mixed $filterItem): array
    {
        if ($filterItem instanceof User === false) {
            return array();
        }

        $allUsers = $this->getAll();
        return array_filter($allUsers, function (User $user) use ($filterItem): bool {
            return $user->hasSimilaritiesWith($filterItem);
        });
    }

    function getAll(): array
    {
        global $db;
        $qry = "SELECT guid, name, surname, username, email, password FROM users";
        $results = $db->query($qry);

        if (empty($results)) {
            return array();
        }

        $allUsers = array();
        foreach ($results->fetch_all(MYSQLI_ASSOC) as $result) {
            $allUsers[] = new User(
                $result["name"], $result["surname"], $result["username"],
                $result["email"], $result["password"], $result["guid"]
            );
        }

        return $allUsers;
    }

    function tryUpdate(?string $guid, mixed $newItem): bool
    {
        throw new Exception("Method is obsolete for this class");
    }

    function tryDelete(string $guid): bool
    {
        throw new Exception("Method is obsolete for this class");
    }
}