// Mostrar pedidos
$sql = "SELECT * FROM pedidos WHERE estado = 'En proceso' OR estado = 'Entregado'";
$stmt = $pdo->query($sql);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<table class="table">
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Tipo de Pago</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?php echo $pedido['id']; ?></td>
            <td><?php echo $pedido['nombre']; ?></td>
            <td><?php echo $pedido['total']; ?></td>
            <td><?php echo $pedido['tipo_pago']; ?></td>
            <td><?php echo $pedido['estado']; ?></td>
            <td>
                <?php if ($pedido['estado'] == 'En proceso'): ?>
                    <a href="marcar_en_camino.php?pedido_id=<?php echo $pedido['id']; ?>" class="btn btn-warning">Marcar en Camino</a>
                <?php elseif ($pedido['estado'] == 'Entregado'): ?>
                    <span class="badge bg-success">Entregado</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
