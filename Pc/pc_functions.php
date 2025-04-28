<?php
require_once 'dbconnect.php';

function get_Infos_cons_main($nomPc) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM hardware WHERE NAME = ?");
    $stmt->execute([$nomPc]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

function get_Infos_soft_main($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM software WHERE HARDWARE_ID = ?");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function get_stat_os($osname) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hardware WHERE OSNAME LIKE ?");
    $stmt->execute(["%$osname%"]);
    return $stmt->fetchColumn();
}

function get_lst_pc() {
    global $pdo;
    $stmt = $pdo->query("SELECT NAME, LASTDATE, LASTCOME FROM hardware");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insert_pc($data) {
    global $pdo;
    $sql = "INSERT INTO hardware (NAME, OSNAME, OSVERSION, ARCHITECTURE, USER, MEMORY, CPU, SERIAL, MAC, IPADDR, DOMAIN, WINPRODKEY, licensestatus, UUID) 
            VALUES (:name, :os_name, :os_version, :architecture, :user, :ram, :cpu, :serial, :mac, :ip, :domaine, :windows_key, :license_status, :uuid)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
}

function update_pc($name, $data) {
    global $pdo;
    $sql = "UPDATE hardware SET 
                NAME = :name,
                OSNAME = :os_name,
                OSVERSION = :os_version,
                ARCHITECTURE = :architecture,
                USER = :user,
                MEMORY = :ram,
                CPU = :cpu,
                SERIAL = :serial,
                MAC = :mac,
                IPADDR = :ip,
                DOMAIN = :domaine,
                WINPRODKEY = :windows_key,
                licensestatus = :license_status,
                UUID = :uuid
            WHERE NAME = :original_name";
    $data['original_name'] = $name;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
}
?>
