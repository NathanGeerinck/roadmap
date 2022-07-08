<?php

namespace App\Http\Livewire\Item;

use App\Models\Item;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public Item $item;

    public function mount()
    {
        $this->form->fill([
            'title' => $this->item->title,
            'content' => $this->item->content,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(trans('table.title'))
                ->minLength(3)
                ->required(),
            MarkdownEditor::make('content')
                ->label(trans('table.content'))
                ->minLength(10)
                ->required(),
        ];
    }

    public function submit()
    {
        $formState = $this->form->getState();

        $this->item->update([
            'title' => $formState['title'],
            'content' => $formState['content'],
        ]);

        if (!$this->item->project) {
            $this->redirectRoute('items.show', $this->item);

            return;
        }

        $this->redirectRoute('projects.items.show', [$this->item->project, $this->item]);
    }

    public function render()
    {
        return view('livewire.item.edit');
    }
}
