<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Widgets\TableWidget as BaseWidget;

class WaitingTransactions extends BaseWidget
{
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->where('status', 'waiting')
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('listing.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_day')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) : string => match($state){
                        'waiting'=> 'warning',
                        'approved'=> 'success',
                        'canceled'=> 'danger',
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('approve')
                ->button()
                ->color('success')
                ->requiresConfirmation()
                ->action(function (Transaction $transaction){
                    Transaction::find($transaction->id)->update([
                        'status' => 'approved'
                    ]);
                    Notification::make()->success()->title('Transaction Approved!')->body('Transaction has been approved successfull');
                })
                ->hidden(fn(Transaction $transaction) => $transaction->status != 'waiting')
            ]);
    }
}
