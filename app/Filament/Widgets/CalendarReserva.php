<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\Horario;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class CalendarReserva extends FullCalendarWidget
{
    protected static ?string $heading = 'Calendario de Reservas';
    public Model | string | null $model = Horario::class;

    // Listener para eventos Livewire
    protected $listeners = ['openEventModal'];

    // Propiedad para almacenar los datos del evento
    public array $eventData = [];

    // Método para decidir si el widget debe ser visible
    public static function canView(): bool
    {
        return !request()->routeIs('filament.admin.pages.dashboard'); // Ocultar en el dashboard principal
    }

    // Configuración de FullCalendar
    public function config(): array
    {
        return [
            'firstDay' => 1,
            'slotMinTime' => '06:00:00',
            'slotMaxTime' => '22:00:00',
            'slotDuration' => '00:30:00',
            'locale' => 'es',
            'initialView' => 'timeGridWeek',
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'timeGridWeek,timeGridDay',
            ],
            'editable' => false,
            'selectable' => false,
            'eventClick' => 'function(info) {
                window.livewire.emit("openEventModal", info.event.id);
            }',
        ];
    }

    // Método para obtener eventos de la base de datos
    public function fetchEvents(array $fetchInfo): array
    {
        return Horario::query()
            ->where('is_available', true)
            ->whereBetween('start_at', [$fetchInfo['start'], $fetchInfo['end']])
            ->get()
            ->map(function (Horario $horario) {
                return [
                    'id' => $horario->id_horario,
                    'title' => $horario->title,
                    'start' => $horario->start_at,
                    'end' => $horario->end_at,
                    'color' => $horario->color ?? '#28a745',
                ];
            })
            ->toArray();
    }


    protected function modalActions(): array
    {
        return [
            Action::make('reservar')
                ->label('Reservar')
                ->color('primary')
                ->icon('heroicon-o-check-circle'),
        ];
    }



    public function getFormSchema(): array
    {
        return [
            Section::make('Horario')
                ->schema([
                    Grid::make(2) // Dividido en dos columnas
                        ->schema([
                            DateTimePicker::make('start_at')
                                ->required()
                                ->label('Fecha y hora de inicio')
                                ->placeholder('Seleccione la fecha y hora de inicio')
                                ->displayFormat('Y-m-d H:i')
                                ->helperText('No se puede seleccionar una fecha pasada')
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state && $state < now()) {
                                        $set('start_at', null); // Limpia el campo si es inválido
                                        return 'La fecha de inicio no puede ser anterior a la actual.';
                                    }
                                }),

                            DateTimePicker::make('end_at')
                                ->required()
                                ->label('Fecha y hora de fin')
                                ->placeholder('Seleccione la fecha y hora de fin')
                                ->displayFormat('Y-m-d H:i')
                                ->helperText('Debe ser posterior a la fecha de inicio')
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    if ($state && $state < $get('start_at')) {
                                        $set('end_at', null); // Limpia el campo si es inválido
                                        return 'La fecha de fin debe ser posterior a la de inicio.';
                                    }
                                }),
                        ]),
                ])
                ->columns(2), // Diseño en dos columnas
        ];
    }
}
