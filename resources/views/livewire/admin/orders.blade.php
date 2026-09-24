<div class="orders-page">

    <div class="orders-header">
        <h1>Gestion des commandes</h1>
        <p>Consultez et recherchez les commandes clients.</p>
    </div>

    

    <div class="filters">

        <div class="filter-group">
            <label for="search">Recherche</label>

            <input
                id="search"
                type="text"
                wire:model="search"
                placeholder="Référence, client ou e-mail"
            >
        </div>

        <div class="filter-group">
            <label for="status">Statut</label>

            <select id="status" wire:model="status">
                <option value="">Tous les statuts</option>

                @foreach (\App\Models\Order::STATUSES as $orderStatus)
                    <option value="{{ $orderStatus }}">
                        {{ ucfirst($orderStatus) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label for="dateFrom">Date début</label>

            <input
                id="dateFrom"
                type="date"
                wire:model="dateFrom"
            >
        </div>

        <div class="filter-group">
            <label for="dateTo">Date fin</label>

            <input
                id="dateTo"
                type="date"
                wire:model="dateTo"
            >
        </div>

        <button
            type="button"
            class="reset-button"
            wire:click="resetFilters"
        >
            Réinitialiser
        </button>

    </div>

    <div class="stats">

    <div class="stat-card">
        <span class="stat-label">Commandes</span>

        <strong class="stat-value">
            {{ number_format($orderCount, 0, ',', ' ') }}
        </strong>
    </div>

    <div class="stat-card">
        <span class="stat-label">Chiffre d'affaires</span>

        <strong class="stat-value">
            {{ number_format($revenue, 2, ',', ' ') }} €
        </strong>
    </div>

    <div class="stat-card">
        <span class="stat-label">Panier moyen</span>

        <strong class="stat-value">
            {{ number_format($averageBasket, 2, ',', ' ') }} €
        </strong>
    </div>

</div>

    <div class="orders-table-container">

        <table class="orders-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Articles</th>
                    <th>Montant</th>
                    <th>Statut</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($orders as $order)

                    <tr>
                        <td class="order-reference">
                            {{ $order->reference }}
                        </td>

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

                        <td class="order-amount">
                            {{ number_format($order->total_amount, 2, ',', ' ') }} €
                        </td>

                        <td>
                            <span class="status status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6">
                            Aucune commande ne correspond aux critères.
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>

    </div>

</div>