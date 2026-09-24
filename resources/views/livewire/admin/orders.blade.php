<div>
    <h1>Gestion des commandes</h1>

    <div>
        <input
            type="text"
            wire:model="search"
            placeholder="Référence, client ou e-mail">

        <select wire:model="status">
            <option value="">Tous les statuts</option>

            @foreach (\App\Models\Order::STATUSES as $orderStatus)
            <option value="{{ $orderStatus }}">
                {{ ucfirst($orderStatus) }}
            </option>
            @endforeach
        </select>

        <input
            type="date"
            wire:model="dateFrom">

        <input
            type="date"
            wire:model="dateTo">
    </div>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Date</th>
                <th>Client</th>
                <th>Nombre d'articles</th>
                <th>Montant</th>
                <th>Statut</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->reference }}</td>

                <td>
                    {{ $order->created_at->format('d/m/Y') }}
                </td>

                <td>
                    {{ $order->customer->firstname }}
                    {{ $order->customer->lastname }}
                </td>

                <td>
                    {{ $order->details_count }}
                </td>

                <td>
                    {{ number_format($order->total_amount, 2, ',', ' ') }} €
                </td>

                <td>
                    {{ $order->status }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>